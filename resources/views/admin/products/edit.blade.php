@extends('layouts.admin')

@section('content')
<div class = "container"> 
    <h1 class="text-center text-primary">Edit a product</h1>
<form action = "{{ route('admin.products.update',$product->id) }}" method = "POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" class="form-control" id="name" name = 'name' value = "{{ $product->name }}">
    </div> 
    <div class="mb-3">
      <label for="description" class="form-label">description</label>
      <input type = "text" class="form-control" id="description" name = 'description' value = "{{ $product->description }}">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">price</label>
        <input type ="text" class="form-control" id="price" name = 'price' value ={{ $product->price }}>
    </div>

    <div class="mb-3">
        <label for="amount">amount :</label>
        <input type="number" class="form-control w-10" id="amount" name="amount" min="1" step="1" value = {{ $product->amount }}>
    </div>

    <div class="mb-3">
        <label for="category" class="form-label">category</label>
        <input type ="text" class="form-control" id="category" name = 'category' value ="{{ $product->category }}">
    </div>


    <div class="mb-3">
        <label for="buy_price" class="form-label">buy price</label>
        <input type ="text" class="form-control" id="buy_price" name = 'buy_price' value ={{ $product->buy_price }}>
    </div>


    <div class="mb-3">
        <label for="supplier_id" class="form-label">Supplier</label>
        <select class="form-control" id="supplier_id" name="supplier_id" required>
            <option value="">-- Select Supplier --</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" 
                    {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>
    </div>


    <div class="mb-3">
        <label for="image" class="form-label">Product Image</label>
        <input type="file" class="form-control" id="image" name="image">
    </div>

    <!-- Display Current Image -->
    @if($product->image_link)
    <div class="mb-3">
        <p>Current Image:</p>
        <img src="{{ asset('storage/' . $product->image_link) }}" alt="Product Image" style="width: 150px; height: auto;">
    </div>
    @endif


    
    <button type="submit" class="btn btn-primary">Save</button>
    
  </form>
  <a href ="{{ route('products.index') }}" class = 'btn btn-secondary'>Back</a>
</div>


@endsection