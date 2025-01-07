@extends('layouts.admin')

@section('content')
<div class="album py-5 bg-light mb-3">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            @foreach($products as $product)
            <div class="col">
            <div class="card shadow-sm">
            <img src="{{asset('storage/images/picture1.png')}}" class="card-img-top" alt="...">

                <div class="card-body">
                <p class="card-text">{{$product->name}}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                    <a href="{{route("products.show", $product->id)}}" class="btn btn-sm btn-outline-secondary">Chi tiết</a>
                    <a class="btn btn-sm btn-outline-secondary">Xoá</a>
                    </div>
                    <small class="text-muted">{{$product->price}} VND</small>
                </div>
                </div>
            </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{$products->links("pagination::bootstrap-5")}}

@endsection