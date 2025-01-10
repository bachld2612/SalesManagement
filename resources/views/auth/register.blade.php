@extends('layouts.auth')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow" style="max-width: 500px; width: 100%; border-radius: 20px;">
            <div class="card-header bg-primary text-white text-center">
                <h1 class="mb-0">Register</h1>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Name</label>
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder="Enter your full name" required>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label fw-bold">Address</label>
                        <input type="text" class="form-control" name="address" id="address"
                            placeholder="Enter your address" required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold">Phone</label>
                        <input type="text" class="form-control" name="phoneNumber" id="phone"
                            placeholder="Enter your phone number" required>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username</label>
                        <input type="text" class="form-control" name="username" id="username"
                            placeholder="Choose a username" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Enter your password" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <small>Already have an account? <a href="{{ route('login') }}"
                        class="text-primary text-decoration-none">Login here</a></small>
            </div>
        </div>
    </div>
@endsection
