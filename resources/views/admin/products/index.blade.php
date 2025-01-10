@extends('layouts.dashboard')

@section('main-content')
    <div class="album py-5 bg-light mb-3">
        <div class="container">

            <!-- Nút Thêm sản phẩm ở đầu trang -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-3">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Thêm sản phẩm</a>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                @foreach ($products as $product)
                    <div class="col">
                        <div class="card shadow-sm">
                            <img src="{{ asset('storage/' . $product->image_link) }}" class="card-img-top" alt="...">

                            <div class="card-body">
                                <p class="card-text">{{ $product->name }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.products.show', $product->id) }}"
                                            class="btn btn-sm btn-outline-primary me-2">Chi tiết</a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="btn btn-sm btn-outline-warning me-2">Sửa</a>
                                    </div>
                                    <small class="text-muted">{{ $product->price }} VND</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{ $products->links('pagination::bootstrap-5') }}
@endsection
