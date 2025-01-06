<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rate;
use App\Models\Product;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $productId)
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Kiểm tra và validate dữ liệu từ form
        $request->validate([
            'star' => 'required|integer|between:1,5',
            'description' => 'nullable|string|max:500',
        ]);

        // Lưu đánh giá vào cơ sở dữ liệu
        Rate::create([
            'product_id' => $productId,
            'user_id' => Auth::user()->id,
            'star' => $request->star,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('customer.products.rate',compact('product'));
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
        //
    }
}
