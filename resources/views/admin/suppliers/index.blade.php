@extends('layouts.dashboard')

@section('main-content')
    <div class="album py-5 bg-light mb-3">
        <div class="container">
            <h1 class="text-center mb-5">Danh sách nhà cung cấp</h1>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary mb-3">Thêm nhà cung cấp</a>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                @foreach ($suppliers as $supplier)
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <p class="card-text">{{ $supplier->name }}</p>
                                <p class="card-text">Email: {{ $supplier->email }}</p>
                                <p class="card-text">Số điện thoại: {{ $supplier->phone_number }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{ route('suppliers.show', $supplier->id) }}"
                                            class="btn btn-sm btn-outline-secondary">Chi tiết</a>
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                            class="btn btn-sm btn-outline-secondary">Sửa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{ $suppliers->links('pagination::bootstrap-5') }}
@endsection
