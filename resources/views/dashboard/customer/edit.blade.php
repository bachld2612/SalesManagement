@extends('layouts.dashboard')

@section('main-content')
    <div class="col-lg-12 col-md-12">
        <div class="card" style="padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border: none;">
            <div class="card-header bg-primary text-white text-center" style="border-radius: 8px 8px 0 0;">
                <h3>Chỉnh Sửa Người Dùng</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="username">Tên Đăng Nhập: </label>
                        <input type="text" class="form-control custom-style" id="username" name="username"
                            value="{{ $user->username }}" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật Khẩu:</label>
                        <input type="password" class="form-control custom-style" id="password" name="password">
                        <small class="form-text text-muted">Để trống nếu không muốn thay đổi.</small>
                    </div>
                    <div class="form-group">
                        <label for="fullname">Họ và Tên: {{ $user->username }}</label>
                        <input type="text" class="form-control custom-style" id="fullname" name="fullname"
                            value="{{ $user->fullname }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Địa Chỉ:</label>
                        <input type="text" class="form-control custom-style" id="address" name="address"
                            value="{{ $user->address }}">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Số Điện Thoại:</label>
                        <input type="text" class="form-control custom-style" id="phone_number" name="phone_number"
                            value="{{ $user->phone_number }}">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Cập Nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection

<style>
    .card {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border: none;
        margin-top: 20px;
    }

    .card-header {
        background-color: #007bff;
        /* Bootstrap primary color */
        color: #fff;
        text-align: center;
        border-radius: 8px 8px 0 0;
        padding: 10px 0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: bold;
    }

    .form-control {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 10px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .w-100 {
        width: 100%;
    }
</style>
