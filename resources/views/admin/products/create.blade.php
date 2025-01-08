@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="text-center text-primary">Thêm sản phẩm</h1>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" id="name" name='name'>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <input type="text" class="form-control" id="description" name='description'>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Giá bán</label>
            <input type="text" class="form-control" id="price" name='price'>
        </div>

        <div class="mb-3">
            <label for="amount">Số lượng :</label>
            <input type="number" class="form-control w-10" id="amount" name="amount" min="1" step="1">
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Thể loại</label>
            <input type="text" class="form-control" id="category" name='category'>
        </div>


        <div class="mb-3">
            <label for="buy_price" class="form-label">Giá nhập</label>
            <input type="text" class="form-control" id="buy_price" name='buy_price'>
        </div>


        <div class="mb-3">
            <label for="supplier_id" class="form-label">Nhà cung cấp</label>
            <select class="form-control" id="supplier_id" name="supplier_id" required>
                <option value="">-- Select Supplier --</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}"
                    {{ $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label for="image" class="form-label">Ảnh sản phẩm</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>


        <button type="submit" class="btn btn-primary">Thêm</button>

    </form>
    <a href="{{ route('products.index') }}" class='btn btn-secondary'>Trở về</a>
</div>

@endsection