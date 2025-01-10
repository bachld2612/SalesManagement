@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Top sản phẩm được yêu thích nhất</h2>

    @if($topFavouriteProducts->isNotEmpty())
        <div class="row">
            @foreach($topFavouriteProducts as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <img src="{{ asset('storage/'.$product->image_link) }}" class="card-img-top">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Sô lượng yêu thích: {{ $product->favorite_count }} </p>
                            <p class="card-text">Gía: {{ $product->price }} </p>
                            <a href="{{ route('customer.products.show', $product->id) }}" class="btn btn-primary">Xem chi tiết</a>
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
