@extends('layouts.dashboard')

@section('main-content')
    <div class="col-lg-12 col-md-12">
        <div class="card" style="padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border: none;">
            <div class="card-header bg-primary text-white text-center" style="border-radius: 8px 8px 0 0;">
                <h3>Thêm Mới Người Dùng</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.employees.store') }}" method="POST">
                    {{-- <form action="" method="POST"> --}}

                    @csrf

                    <div class="form-group">
                        <label for="username">Tên Đăng Nhập:</label>
                        <input type="text" class="form-control custom-style" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật Khẩu:</label>
                        <input type="password" class="form-control custom-style" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="fullname">Họ và Tên:</label>
                        <input type="text" class="form-control custom-style" id="fullname" name="fullname">
                    </div>
                    <div class="form-group">
                        <label for="address">Địa Chỉ:</label>
                        <input type="text" class="form-control custom-style" id="address" name="address">
                    </div>
                    <div class="form-group">
                        <label for="position">Chức vụ:</label>
                        <select class="form-control custom-style" id="position" name="position">
                            <option value="staff">Staff</option>
                            <option value="manager">Manager</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Số Điện Thoại:</label>
                        <input type="text" class="form-control custom-style" id="phone_number" name="phone_number">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Lưu</button>
                </form>
            </div>
        </div>
    </div>
    <style>
        .custom-style {
            background-color: #f7f7f7;
            /* Same color for all */
            border: 1px solid #ced4da;
            padding: 10px;
            border-radius: 4px;
        }

        .custom-style:focus {
            background-color: #fff;
            /* White background on focus */
        }
    </style>
@endsection
