@extends('layouts.app')


@section('content')
<div class="card-body">
    <h5 class="card-title">{{$product->name}}</h5>
    <p class="card-text">Mô tả: {{$product->description}}</p>
    <p class="card-text">Giá tiền: {{$product->price}} VNĐ</p>
    <p class="card-text">Thể loại: {{$product->category}} VNĐ</p>
    <p class="card-text">Số lượng: {{$product->amount}} </p>

    <!-- Form Đánh Giá -->
    @auth
    <form action="{{ route('rates.store', $product->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="star" class="form-label">Đánh giá sao</label>
            <select name="star" id="star" class="form-select" required>
                <option value="1">1 sao</option>
                <option value="2">2 sao</option>
                <option value="3">3 sao</option>
                <option value="4">4 sao</option>
                <option value="5">5 sao</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Bình luận</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">
            Gửi đánh giá
        </button>
    </form>
    @endauth

    <div class="d-flex justify-content-start gap-2 mt-4">
        <a href="{{ route('customer.products.purchased') }}" class="btn btn-secondary ">Trở về</a>
        <button class="btn btn-success ">Mua lại</button>
    </div>
</div>

@endsection