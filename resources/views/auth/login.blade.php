@extends('layouts.auth')
@section('content')
    {{-- <div class="container">
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
    </div> --}}


    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .card {
            border-radius: 20px;
            overflow: hidden;
        }

        .card-header {
            background: #2575fc;
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #6a11cb;
            border: none;
        }

        .btn-primary:hover {
            background-color: #5a0db5;
        }

        .form-label {
            font-weight: bold;
        }
    </style>
    </head>

    <body>
        <div class="container">
            <div class="card shadow" style="max-width: 500px; margin: auto;">
                <div class="card-header text-center">Login</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username" required autofocus>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <small>Don't have an account? <a href="{{ route('register') }}"
                            class="text-primary text-decoration-none">Register here</a></small>
                </div>
            </div>
        </div>
    @endsection
