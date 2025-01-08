@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Danh sách sản phẩm bạn đã mua</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    @if($purchasedProducts->isNotEmpty())
        <div class="row">
            @foreach($purchasedProducts as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ $product->image_link ? asset('storage/'.$product->image_link) : asset('images/default-product.png') }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Giá: {{ number_format($product->price, 2) }} VND</p>
                            <p class="card-text">Số lượng: {{ $product->amount }}</p>

                            @if($product->rated)
                                <button class="btn btn-success" disabled>Đã gửi đánh giá</button>
                            @else
                                <a href="{{ route('rates.show', $product->id) }}" class="btn btn-primary">Thêm đánh giá</a>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            Bạn chưa mua sản phẩm nào.
        </div>
    @endif
</div>
@endsection
