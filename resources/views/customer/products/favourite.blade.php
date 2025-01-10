@extends('layouts.app')

@section('content')


@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="album py-5 bg-light mb-3">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            @foreach($favouriteProducts1 as $product)
            <div class="col">
            <div class="card shadow-sm">
            <img src="{{asset('storage/'.$product->image_link)}}" class="card-img-top" alt="...">

                <div class="card-body">
                <p class="card-text">{{$product->name}}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                    <a href="{{route("customer.products.show", $product->id)}}" class="btn btn-sm btn-outline-secondary">Chi tiết</a>
                    <form action="{{ route('customer.favourites.toggle', $product->id) }}" method="POST" style="display: inline;">
                      @csrf
                      <button 
                          type="submit" 
                          class="btn btn-sm {{ in_array($product->id, $favouriteProducts) ? 'btn-danger' : 'btn-outline-secondary' }}">
                          {{ in_array($product->id, $favouriteProducts) ? 'Đã yêu thích' : 'Yêu thích' }}
                      </button>
                  </form>

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




@endsection