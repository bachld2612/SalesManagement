<?php

namespace App\Http\Controllers\products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(6);
      
        if (Auth::check() && Auth::user()->role_name === 'admin') {
            return view('admin.products.index', compact('products'));
        } else {
            return view('welcome', compact('products'));
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
    return redirect()->route('products.index')->with('success', 'Product created successfully!');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $product = Product::findOrFail($id);
        if (Auth::check() && Auth::user()->role_name === 'admin') {
            return view('admin.products.show', compact('product'));
        } else {
            return view('customer.products.show', compact('product'));
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

    return redirect()->route('products.index')->with('success', 'Product updated successfully!');
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
    return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
}

}
