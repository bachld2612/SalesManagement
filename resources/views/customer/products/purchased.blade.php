@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Danh sách sản phẩm bạn đã mua</h2>
    
    @if(count($purchasedProducts) > 0)
        <div class="row">
            @foreach($purchasedProducts as $product)
                <div class="col-md-4">
                    <div class="card">
                        <img src="{{ asset('storage/'.$product->image_link) }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Giá: {{ number_format($product->price, 2) }} VND</p>
                            <p class="card-text">Số lượng: {{ $product->amount }}</p>
                            <a href="{{ route('rates.show', $product->id) }}" class="btn btn-primary">Thêm đánh giá</a>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Bạn chưa mua sản phẩm nào.</p>
    @endif
</div>
@endsection
