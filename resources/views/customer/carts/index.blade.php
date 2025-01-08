@extends('layouts.app')
@section('content')
<h1 class="text-center text-primary">Giỏ hàng</h1>
@if (@session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (@session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif
<!-- Form bao quanh bảng sản phẩm -->
<form action="{{ route('customer.carts.handle') }}" method="POST">
    @csrf
    <input type="hidden" name="action" id="form-action" value="">

    <table class="table">
        <thead>
            <tr>
                <th scope="col">STT</th>
                <th scope="col">Tên sản phẩm</th>
                <th scope="col">Số lượng</th>
                <th scope="col">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $item->product->name }}</td>
                <td>
                    <div class="form-group" style="max-width: 150px;">
                        <input
                            type="number"
                            id="quantity-{{ $item->id }}"
                            name="quantities[{{ $item->id }}]"
                            class="form-control text-center"
                            value="{{ $item->amount }}"
                            min="1"
                            max="{{ $item->product->amount }}">
                    </div>
                </td>
                <td>
                    <!-- Nút Xóa -->
                    <button
                        type="submit"
                        name="action"
                        value="delete-{{ $item->id }}"
                        class="btn btn-danger">
                        Xoá
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Nút Mua hàng -->
    <div class="d-flex justify-content-end">
        <button type="submit" name="action" value="checkout" class="btn btn-primary">Mua hàng</button>
    </div>
</form>
@endsection
