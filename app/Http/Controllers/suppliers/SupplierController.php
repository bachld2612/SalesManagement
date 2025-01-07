<?php

namespace App\Http\Controllers\suppliers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::paginate(6);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email',
                'phone_number' => 'required',
                'regex:/^0\d{9}$/',
            ],
            [
                'name.required' => 'Tên nhà cung cấp là bắt buộc.',
                'email.required' => 'Email là bắt buộc.',
                'email.email' => 'Email không hợp lệ.',
                'phone_number.required' => 'Số điện thoại là bắt buộc.',
                'phone_number.regex' => 'Số điện thoại phải bắt đầu bằng số 0 và có 10 chữ số.',
            ]
        );

        Supplier::create($validate);
        return redirect()->route('suppliers.index')->with('success', 'Thêm mới nhà cung cấp thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::with('products')
            ->findOrFail($id);
        $products = $supplier->products()->paginate(6);
        return view('admin.suppliers.show', compact('supplier', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $validate = $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email',
                'phone_number' => 'required',
                'regex:/^0\d{9}$/',
            ],
            [
                'name.required' => 'Tên nhà cung cấp là bắt buộc.',
                'email.required' => 'Email là bắt buộc.',
                'email.email' => 'Email không hợp lệ.',
                'phone_number.required' => 'Số điện thoại là bắt buộc.',
                'phone_number.regex' => 'Số điện thoại phải bắt đầu bằng số 0 và có 10 chữ số.',
            ]
        );
        $supplier->update($validate);
        return redirect()->route('suppliers.index')->with('success', 'Cập nhập thành công nhà cung cấp');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
