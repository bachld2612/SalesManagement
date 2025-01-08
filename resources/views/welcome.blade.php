@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="alert alert-success">
  {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
  {{ session('error') }}
</div>
@endif


<div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="{{ asset('storage/images/adsimage6.jpg') }}" class="d-block w-100" alt="Image 1">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('storage/images/adsimage5.jpg') }}" class="d-block w-100" alt="Image 2">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('storage/images/adsimage4.jpg') }}" class="d-block w-100" alt="Image 3">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>


<div class="album py-5 bg-light mb-3">
  <div class="container">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
      @foreach($products as $product)
      @if ($product->amount > 0)
      <div class="col">
        <div class="card shadow-sm">
          <img src="{{asset('storage/'.$product->image_link)}}" class="card-img-top" alt="...">

          <div class="card-body">
            <p class="card-text">{{$product->name}}</p>
            <div class="d-flex justify-content-between align-items-center">
              <div class="btn-group">
                <a href="{{route("customer.products.show", $product->id)}}" class="btn btn-sm btn-outline-secondary">Chi tiết</a>
                @if(auth()->check())
                <form action="{{ route('customer.favourites.toggle', $product->id) }}" method="POST" style="display: inline;">
                  @csrf
                  <button
                    type="submit"
                    class="btn btn-sm {{ in_array($product->id, $favouriteProducts) ? 'btn-danger' : 'btn-outline-secondary' }}">
                    {{ in_array($product->id, $favouriteProducts) ? 'Đã yêu thích' : 'Yêu thích' }}
                  </button>
                </form>
                @endif

              </div>
              <small class="text-muted">{{$product->price}} VND</small>
            </div>
          </div>
        </div>
      </div>
      @endif
      @endforeach
    </div>
  </div>
</div>


{{$products->links("pagination::bootstrap-5")}}

@endsection