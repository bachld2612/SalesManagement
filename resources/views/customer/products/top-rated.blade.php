@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Top sản phẩm được đánh giá cao nhất</h2>

    @if($topRatedProducts->isNotEmpty())
        <div class="row">
            @foreach($topRatedProducts as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->product_name }}</h5>
                            <p class="card-text">Đánh giá trung bình: {{ number_format($product->average_rating, 1) }} / 5</p>
                            <a href="{{ route('customer.products.show', $product->product_id) }}" class="btn btn-primary">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            Không có sản phẩm được đánh giá.
        </div>
    @endif
</div>
@endsection
