@extends('layouts.auth')
@section('content')
<div class="container">
    <h1 class="text-center mb-3 text-primary">Đăng nhập</h1>
    <main class="form-signin">
        <form method="post" action="{{ route(name: 'login') }}">
            @csrf
            <div class="form-floating">
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username">
                <label for="username">Tên đăng nhập</label>
            </div>
            <div class="form-floating">
                
                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                <label for="password" class="form-label">Mật khẩu</label>
            </div>
        
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
            <button type="button" class="btn btn-primary">
                <a href="{{ route('products.index') }}" style="color: white; text-decoration: none;">Hủy</a>
            </button>
        </form>
    </main>

</div>
@endsection