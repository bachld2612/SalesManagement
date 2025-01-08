<?php

namespace App\Http\Controllers\products;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FavouriteList;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    
    if (Auth::check() && Auth::user()->role_name === 'admin') {
        $products = Product::paginate(6);
        return view('admin.products.index', compact('products'));
    } else {
        $products = Product::where('amount','>',0)->paginate(6);
        $favouriteProducts = Auth::check()
            ? FavouriteList::where('user_id', Auth::user()->id)->pluck('product_id')->toArray()
            : [];
        return view('welcome', compact('products', 'favouriteProducts'));
    }
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('admin.products.create',compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
   /**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    // Validate dữ liệu từ form
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'amount' => 'required|integer|min:1',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'category' => 'nullable|string|max:255',
        'buy_price' => 'required|numeric|min:0',
        'supplier_id' => 'required'
    ]);

    // Tạo mới một đối tượng sản phẩm
    $product = new Product();

    // Gán các giá trị vào đối tượng sản phẩm
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->amount = $request->amount;
    $product->category = $request->category;
    $product->supplier_id = $request->supplier_id;
    $product->buy_price = $request->buy_price;

    // Xử lý ảnh nếu có file mới được tải lên
    if ($request->hasFile('image')) {
        // Lưu ảnh vào thư mục storage
        $path = $request->file('image')->store('images', 'public');
        $product->image_link = $path;
    }

    // Lưu sản phẩm vào cơ sở dữ liệu
    $product->save();

    // Chuyển hướng về trang danh sách sản phẩm với thông báo thành công
    return redirect()->route('products.index')->with('success', 'Đã thêm sản phẩm thành công!');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $product = Product::findOrFail($id);
    $favouriteCount = 0;

    // Gọi stored procedure và nhận giá trị trả về
    $result = DB::select('EXEC sp_GetFavoriteCount ?', [$id]);

    // Kiểm tra nếu có kết quả trả về và lấy giá trị favourite_count
    if (count($result) > 0) {
        $favouriteCount = $result[0]->favorite_count ?? 0;
    }

    if (Auth::check() && Auth::user()->role_name === 'admin') {
        return view('admin.products.show', compact('product','favouriteCount'));
    } else {
        return view('customer.products.show', compact('product', 'favouriteCount'));
    }
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $suppliers = Supplier::all();
        $product = Product::findOrFail($id);
        if (Auth::check() && Auth::user()->role_name === 'admin') {
            return view('admin.products.edit', compact('product','suppliers'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'amount' => 'required|integer|min:1',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'category' => 'nullable|string|max:255',
        'buy_price' => 'required|numeric|min:0',
        'supplier_id' => 'required'
    ]);

    // Cập nhật dữ liệu sản phẩm
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->amount = $request->amount;
    $product->category = $request->category;
    $product->supplier_id = $request->supplier_id;
    $product->buy_price = $request->buy_price;

    // Xử lý ảnh nếu có file mới được tải lên
    if ($request->hasFile('image')) {
        // Xóa ảnh cũ nếu tồn tại
        if ($product->image_link && Storage::exists('public/images/' . $product->image_link)) {
            Storage::delete('public/images/' . $product->image_link);
        }
    
        // Lưu ảnh mới
        $path = $request->file('image')->store('images', 'public');
        $product->image_link = $path;
    }
    

    $product->save();

    return redirect()->route('products.index')->with('success', 'Đã cập nhật sản phẩm thành công!');
}


    /**
     * Remove the specified resource from storage.
     */
   /**
 * Remove the specified resource from storage.
 */
public function destroy(string $id)
{
    // Tìm sản phẩm cần xóa
    $product = Product::findOrFail($id);

    // Xóa ảnh nếu tồn tại
    if ($product->image_link && Storage::exists('public/images/' . $product->image_link)) {
        Storage::delete('public/images/' . $product->image_link);
    }

    // Xóa sản phẩm
    $product->delete();

    // Chuyển hướng về trang danh sách sản phẩm với thông báo thành công
    return redirect()->route('products.index')->with('success', 'Đã xóa sản phẩm thành công!');
}


// Hàm để lấy tất cả sản phẩm yêu thích của người dùng
    public function getUserFavouriteProducts()
    {
        // Gọi stored procedure và nhận kết quả
        $userId = Auth::user()->id;
        $favouriteProducts1 = DB::select('SELECT * FROM dbo.fn_GetUserFavouriteProducts(?)', [$userId]);
        $favouriteProducts = Auth::check()
        ? FavouriteList::where('user_id', Auth::user()->id)->pluck('product_id')->toArray()
        : [];
        $favouriteProducts1 = collect($favouriteProducts1); 
        // Trả về kết quả ra view
 
        return view('customer.products.favourite', compact('favouriteProducts1','favouriteProducts'));
    }


public function getUserPurchasedProducts()
{
    // Kiểm tra nếu người dùng đã đăng nhập
        $userId = Auth::user()->id;

        // Gọi stored procedure để lấy danh sách sản phẩm đã mua
        $purchasedProducts = DB::select('EXEC sp_GetUserPurchasedProducts ?', [$userId]);

        return view('customer.products.purchased', compact('purchasedProducts'));
    
}


}
