@extends('layouts.app')
@section('content')
<div class="text-center text-primary mb-4">
    <h1>Chi tiết đơn hàng</h1>
</div>
<div class="container">
    @if (auth()->user()->role_name === 'admin')
    <div class="fs-4 mb-3">Họ tên người nhận: {{$order->fullname}}</div>
    <div class="fs-4 mb-3">Địa chỉ: {{$order->address}}</div>
    <div class="fs-4 mb-3">Số điện thoại: {{$order->phone_number}}</div>
    <div class="fs-4 mb-3">Tổng tiền: {{$order->order_price}} VNĐ</div>
    <div class="fs-4 mb-3">Ngày mua hàng: {{ \Carbon\Carbon::parse($order->purchase_date)->format('d/m/Y') }}</div>
    @endif
    <div class="row">
        @foreach ($orderDetails as $index => $orderDetail)
        <!-- Bắt đầu hàng mới sau mỗi 3 sản phẩm -->
        @if ($index % 3 === 0 && $index !== 0)
    </div>
    <div class="row">
        @endif
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{asset('storage/'.$orderDetail->image_link)}}" class="card-img-top" alt="Image">
                <div class="card-body">
                    <h5 class="card-title">{{ $orderDetail->name }}</h5>
                    <p class="card-text">Đơn giá: {{ $orderDetail->price }}</p>
                    <p class="card-text">Số lượng: {{ $orderDetail->amount }}</p>
                    <p class="card-text">Thành tiền: {{ $orderDetail->total_price }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div> <!-- Kết thúc hàng cuối -->
</div>
<a href="{{route('admin.orders.index')}}" class="btn btn-secondary">Trở về</a>
@endsection