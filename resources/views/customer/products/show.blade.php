@extends('layouts.app')
@section('content')
<div class="card">
    <img src="{{asset('storage/images/picture1.png')}}" class="card-img-top" alt="...">

    <div class="card-body">
        <h5 class="card-title">{{$product->name}}</h5>
        <p class="card-text">Mô tả: {{$product->description}}</p>
        <p class="card-text">Giá tiền: {{$product->price}} VNĐ</p>
        <p class="card-text">Thể loại: {{$product->category}} VNĐ</p>
        <p class="card-text">Số lượng: {{$product->amount}} </p>
        <p class="card-text">Số lượng yêu thích: {{$favouriteCount}}</p>
        <a href="{{route('products.index')}}" class="btn btn-primary">Trở về</a>
        <button href="#" class="btn btn-primary">Mua</button>
        <button href="#" class="btn btn-primary">Thêm vào giỏ hàng</button>
    </div>
</div>


@endsection