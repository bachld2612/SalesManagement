<?php

namespace App\Http\Controllers\orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $i = 1;
        if (Auth::check() && Auth::user()->role_name === 'customer') {
            $orders = DB::table('view_OrderInfo')
                ->where('user_id', Auth::user()->id)
                ->orderby('state', 'asc')
                ->paginate(6);
        }else if (Auth::check() && Auth::user()->role_name === 'admin') {
            $orders = DB::table('view_OrderInfo')
            ->orderBy('state', 'asc')
            ->paginate(6);
            // dd($orders);
            return view('admin.orders.index', compact('orders', 'i'));
        }
        return view('customer.orders.index', compact('orders', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function deliverOrder($id){
        $order = Order::findOrFail($id);
        $order->state = 1;
        $order->save();
        return redirect(route('admin.orders.index'))->with('success', 'Đã giao hàng!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orderDetails = DB::table('view_ShowOrderDetails')
            ->where('order_id', $id)
            ->get();

        $order =  DB::table('view_OrderInfo')
            ->where('order_id', $id)
            ->first();


        return view('customer.orders.show', compact('orderDetails', 'order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function completeOrder($id){
        $payment = Payment::create(
            [
                'order_id' => $id,
                'payment_date' => now(),
                'payment_method' => 'Thanh toán khi nhận hàng',
            ]
            );
        return redirect(route('admin.orders.index'))->with('success', 'Đã xác nhận đơn hàng!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->state = 3;
        $order->save();
        return redirect(route('customer.orders.index'))->with('success', 'Đã huỷ đơn hàng!');
    }
}
