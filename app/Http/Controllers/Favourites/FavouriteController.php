<?php

namespace App\Http\Controllers\Favourites;
use Illuminate\Support\Facades\Auth;
use App\Models\FavouriteList;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function toggle($productId)
    {
        $userId = Auth::user()->id; // Lấy ID người dùng hiện tại

        // Kiểm tra xem sản phẩm đã được yêu thích chưa
        $favourite = FavouriteList::where('user_id', $userId)
                              ->where('product_id', $productId)
                              ->first();

        if ($favourite) {
            // Nếu đã yêu thích thì xóa
            $favourite->delete();
            return redirect()->back()->with('success', 'Đã bỏ yêu thích sản phẩm!');
        } else {
            // Nếu chưa yêu thích thì thêm
            FavouriteList::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            return redirect()->back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích!');
        }
    }
}
