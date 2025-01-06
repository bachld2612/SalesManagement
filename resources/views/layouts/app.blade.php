<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


    <style>
        /* CSS để điều chỉnh chiều cao của ảnh */
        .carousel-inner img {
        height: 600px; /* Thay đổi giá trị này để điều chỉnh chiều cao */
        object-fit: cover; /* Đảm bảo ảnh được cắt sao cho phù hợp với chiều cao mà bạn đã định */
        }
    </style>
<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
          <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
            <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
          </a>
    
          <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="#" class="nav-link px-2 link-secondary">Home</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Products</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Carts</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Wishlist</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">About</a></li>
          </ul>
    
          <div class="col-md-3 text-end">
            @if(auth()->check())
            <form  method="post" action="{{route('logout')}}">
                @csrf
                <button type="submit" class="btn btn-outline-primary me-2">Logout</button>
            </form>
            @else
            <button type="button" class="btn btn-outline-primary me-2"><a  style="text-decoration: none; color: inherit" href="{{route('login')}}">Log in</a></button>
            <button type="button" class="btn btn-primary">
                <a href="{{ route('register') }}" style="color: white; text-decoration: none">Register</a>
            </button>
            
            @endif
          </div>
        </header>
      </div>

    @yield('content')

    <footer class="container">
        <p class="float-end"><a href="#">Back to top</a></p>
        <p>&copy; 2017–2021 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>
    </main>
    

</body>
</html>
