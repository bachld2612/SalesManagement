<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">


    <style>
        /* CSS để điều chỉnh chiều cao của ảnh */
        .carousel-inner img {
        height: 600px; /* Thay đổi giá trị này để điều chỉnh chiều cao */
        object-fit: cover; /* Đảm bảo ảnh được cắt sao cho phù hợp với chiều cao mà bạn đã định */
        }

        img {
      width: 100%; /* Đảm bảo ảnh chiếm toàn bộ chiều rộng */
      height: 400px; /* Đặt chiều cao cố định */
      object-fit: contain; /* Đảm bảo ảnh không bị méo */
      border-radius: 5px; /* (Tùy chọn) Bo tròn các góc */
  }
  
    </style>
<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
         
    
          <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="{{route('products.index')}}" class="nav-link px-2 link-secondary">Trang chủ</a></li>
            <li><a href="{{ route('products.index') }}" class="nav-link px-2 link-dark">Sản phẩm</a></li>
            @if(auth()->check())
            <li><a href="{{route('customer.carts.index')}}" class="nav-link px-2 link-dark">Giỏ hàng</a></li>
            <li><a href="{{route('customer.orders.index')}}" class="nav-link px-2 link-dark">Đơn hàng</a></li>
            <li><a href="{{ route('customer.products.favourite') }}" class="nav-link px-2 link-dark">Danh mục yêu thích</a></li>
            <li><a href="{{ route('customer.products.purchased') }}" class="nav-link px-2 link-dark">Đánh giá</a></li>
            <li><a href="{{ route('customer.products.topRated') }}"class="nav-link px-2 link-dark">SP được đánh giá cao</a></li>
            <li><a href="{{ route('customer.products.topFavourite') }}"class="nav-link px-2 link-dark">SP được yêu thích nhiều</a></li>
            @endif
          </ul>
    
          <div class="col-md-3 text-end">
            @if(auth()->check())
            <form  method="post" action="{{route('logout')}}">
                @csrf
                <button type="submit" class="btn btn-outline-primary me-2">Đăng xuất</button>
            </form>
            @else
            <button type="button" class="btn btn-outline-primary me-2"><a  style="text-decoration: none; color: inherit" href="{{route('login')}}">Đăng nhập</a></button>
            <button type="button" class="btn btn-primary">
                <a href="{{ route('register') }}" style="color: white; text-decoration: none">Đăng kí</a>
            </button>
            
            @endif
          </div>
        </header>
      </div>

    <main class="container">@yield('content')</main>

    <footer class="container mt-3">
        <p class="float-end"><a href="#">Quay lại đầu trang</a></p>
        <p>&copy; Công ty TNHH một thành viên</p>
      </footer>
    </main>
    
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
