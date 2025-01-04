@extends('layouts.auth')
@section('content')
<div class="container">
    <h1 class="text-center mb-3 text-primary">Login</h1>
    <form method="post" action="{{ route(name: 'login') }}">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection