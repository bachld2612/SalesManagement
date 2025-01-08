@extends('layouts.app')
@section('content')
<div class="card">
    <img src="{{asset('storage/'.$product->image_link)}}" class="card-img-top" alt="...">

    <div class="card-body">
        <h5 class="card-title">{{$product->name}}</h5>
        <p class="card-text">Mô tả: {{$product->description}}</p>
        <p class="card-text">Giá tiền: {{$product->price}} VNĐ</p>
        <p class="card-text">Thể loại: {{$product->category}} VNĐ</p>
        <p class="card-text">Số lượng: {{$product->amount}} </p>
        <p class="card-text">Số lượng yêu thích: {{$favouriteCount}}</p>
        <div class='d-flex'>
            <a href="{{route('products.index')}}" class="me-3 btn btn-primary">Trở về</a>
            <form action="{{route('customer.carts.store', $product->id)}}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
            </form>
        </div>
    </div>
</div>


@endsection