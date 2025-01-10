@extends('layouts.dashboard')

@section('main-content')
    <h1 class="text-center">{{ $supplier->name }}</h1>
    <div class="fs-4 m-1">Email: {{ $supplier->email }}</div>
    <div class="fs-4 m-1">Số điện thoại: {{ $supplier->phone_number }}</div>

    @if ($supplier->products->count() > 0)
        <div class="text-center fs-4 mb-3">Các sản phẩm hiện có</div>
    @else
        <div class="text-center fs-4 mb-3">Nhà cung cấp chưa có sản phẩm nào</div>
    @endif

    <div class="album py-5 bg-light mb-3">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                @foreach ($products as $product)
                    <div class="col">
                        <div class="card shadow-sm">

                            <div class="card-body">
                                <p class="card-text">Mã sản phẩm: {{ $product->id }}</p>
                                <p class="card-text">Tên sản phẩm: {{ $product->name }}</p>
                                <p class="card-text">Số lượng: {{ $product->amount }}</p>
                                <p class="card-text">Số lượng yêu thích: {{ $product->favorite_count }}</p>
                                <p class="card-text">Giá nhập: {{ $product->buy_price }}</p>
                                <p class="card-text">Giá bán: {{ $product->price }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{ $products->links('pagination::bootstrap-5') }}
@endsection
