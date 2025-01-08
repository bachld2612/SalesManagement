<?php

namespace App\Http\Controllers\carts;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->id;

        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->get();
        $i = 1;
        return view('customer.carts.index', compact('cartItems', 'i'));
    }

    public function handleCart(Request $request)
    {
        $action = $request->input('action');
        $userId = Auth::user()->id;

        if (!$action) {
            return redirect()->back()->with('error', 'Không thể xử lý yêu cầu!');
        }

        if ($action === 'checkout') {
            DB::beginTransaction();
    
            try {
                $cartItems = Cart::where('user_id', $userId)->get();
                if ($cartItems->isEmpty()) {
                    return redirect(route('products.index'))->with('error', 'Giỏ hàng trống, không thể mua hàng!');
                }
    
                // Tạo đơn hàng
                $order = Order::create([
                    'user_id' => $userId,
                    'state' => 0,
                    'purchase_date' => now(),
                    'order_price' => 0,
                ]);
    
                $quantities = $request->input('quantities');
                $orderTotalPrice = 0;
    
                foreach ($cartItems as $cartItem) {
                    $quantity = $quantities[$cartItem->id] ?? 0;
    
                    if ($quantity > 0) {
                        $product = $cartItem->product;
    
                        if ($quantity > $product->amount) {
                            throw new \Exception("Sản phẩm {$product->name} không đủ số lượng!");
                        }
    
                        OrderDetail::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'amount' => $quantity,
                        ]);
    
                        $orderTotalPrice += $product->price * $quantity;
    
                        // Cập nhật số lượng sản phẩm
                        $product->update(['amount' => $product->amount - $quantity]);
                    }
                }
    
                $order->update(['order_price' => $orderTotalPrice]);
    
                // Xoá giỏ hàng
                Cart::where('user_id', $userId)->delete();
    
                DB::commit();
    
                return redirect()->route('customer.orders.index')->with('success', 'Đơn hàng đã được tạo thành công!');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        if (str_starts_with($action, 'delete-')) {
            // Xử lý xóa sản phẩm
            $itemId = explode('-', $action)[1];
            Cart::destroy($itemId);

            return redirect()->route('customer.carts.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
        }

        return redirect()->back()->with('error', 'Hành động không hợp lệ!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $productId)
    {
        $userId = Auth::user()->id;
        $product = Cart::where('user_id',$userId)->where('product_id',$productId)->get();
        if(!$product->isEmpty()){
            return redirect(route('products.index'))->with('error', 'Sản phẩm đã có trong giỏ hàng!');
        }
        Cart::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'amount' => 1
        ]);
        return redirect(route('products.index'))->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    }
}
