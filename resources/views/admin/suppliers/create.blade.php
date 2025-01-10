@extends('layouts.dashboard')

@section('main-content')
    <h1 class="text-center">Thêm nhà cung cấp</h1>
    <form method="post" action="{{ route('suppliers.store') }}" class="container">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-3">
            <label for="name" class="form-label">Tên nhà cung cấp</label>
            <input type="text" required name='name' class="form-control" id="name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" required class="form-control" id="email" name='email'>
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Số điện thoại</label>
            <input type="text" required pattern="^0\d{9}$" title="Số điện thoại không hợp lệ" name='phone_number'
                class="form-control" id="phone_number">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
