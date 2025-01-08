@extends('layouts.auth')
@section('content')
<div class="container">
    <h1 class="text-center mb-3 text-primary">Tạo tài khoản mới</h1>
    <form method="post" action="{{ route(name: 'register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Họ tên</label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" name="address" id="address">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" name="phoneNumber" id="phone">
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" name="username" id="username">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" id="password">
        </div>
        <button type="submit" class="btn btn-primary">Đăng kí</button>
        <button type="button" class="btn btn-primary">
            <a href="{{ route('products.index') }}" style="color: white; text-decoration: none">Hủy</a>
        </button>
    </form>
</div>
@endsection