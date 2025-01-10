<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cửa hàng quần áo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">


    <style>
        /* CSS để điều chỉnh chiều cao của ảnh */
        .carousel-inner img {
            height: 600px;
            /* Thay đổi giá trị này để điều chỉnh chiều cao */
            object-fit: cover;
            /* Đảm bảo ảnh được cắt sao cho phù hợp với chiều cao mà bạn đã định */
        }

        img {
            width: 100%;
            /* Đảm bảo ảnh chiếm toàn bộ chiều rộng */
            height: 200px;
            /* Đặt chiều cao cố định */
            object-fit: contain;
            /* Đảm bảo ảnh không bị méo */
            border-radius: 5px;
            /* (Tùy chọn) Bo tròn các góc */
        }

  }

    </style>

<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-between py-3 mb-4 border-bottom">
            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="{{ route('products.index') }}" class="nav-link px-2 link-secondary">Trang chủ</a></li>
                <li><a href="{{ route('products.index') }}" class="nav-link px-2 link-dark">Sản phẩm</a></li>
                @if (auth()->check())
                    <li><a href="{{ route('customer.carts.index') }}" class="nav-link px-2 link-dark">Giỏ hàng</a></li>
                    <li><a href="{{ route('customer.orders.index') }}" class="nav-link px-2 link-dark">Đơn hàng</a></li>
                    <li><a href="{{ route('customer.products.favourite') }}" class="nav-link px-2 link-dark">Danh mục
                            yêu thích</a></li>
                    <li><a href="{{ route('customer.products.purchased') }}" class="nav-link px-2 link-dark">Đánh
                            giá</a></li>
                    <li><a href="{{ route('customer.products.topRated') }}" class="nav-link px-2 link-dark">SP được đánh
                            giá cao</a></li>
                    <li><a href="{{ route('customer.products.topFavourite') }}" class="nav-link px-2 link-dark">SP được
                            yêu thích nhiều</a></li>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-2 link-dark" href="#" id="notificationDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Thông báo <span id="notificationCount" class="badge bg-danger">0</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="notificationDropdown" id="notificationList">
                        <li class="dropdown-item text-muted">Không có thông báo mới</li>
                    </ul>
                </li>
            </ul>

            <div class="text-end">
                @if (auth()->check())
                    <form method="post" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary me-2">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Đăng kí</a>
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

{{-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        const notificationDropdown = document.getElementById("notificationDropdown");
        const notificationList = document.getElementById("notificationList");
        const notificationCount = document.getElementById("notificationCount");

        async function fetchNotifications() {
            try {
                const response = await fetch("{{ route('notifications.get') }}");
                const notifications = await response.json();

                notificationList.innerHTML = ""; // Clear current list

                if (notifications.length === 0) {
                    notificationList.innerHTML =
                        '<li class="dropdown-item text-muted">Không có thông báo mới</li>';
                    notificationCount.textContent = "0";
                    return;
                }

                // Update notification count
                notificationCount.textContent = notifications.length;

                // Populate notifications
                notifications.forEach((notification) => {
                    const listItem = document.createElement("li");
                    listItem.classList.add("dropdown-item");
                    listItem.innerHTML = `
                    <p class="mb-0">${notification.message}</p>
                    <small class="text-muted">${new Date(notification.created_at).toLocaleString()}</small>
                `;
                    notificationList.appendChild(listItem);
                });
            } catch (error) {
                console.error("Error fetching notifications:", error);
            }
        }

        // Fetch notifications when dropdown is shown
        notificationDropdown.addEventListener("show.bs.dropdown", fetchNotifications);
    });
</script> --}}


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const notificationDropdown = document.getElementById("notificationDropdown");
        const notificationList = document.getElementById("notificationList");
        const notificationCount = document.getElementById("notificationCount");
        async function fetchNotifications() {
            try {
                const countResponse = await fetch("{{ route('notifications.count') }}");
                const countData = await countResponse.json();
                notificationCount.textContent = countData.count;
                const response = await fetch("{{ route('notifications.get') }}");
                const notifications = await response.json();
                notificationList.innerHTML = ""; // Clear current list
                if (notifications.length === 0) {
                    notificationList.innerHTML =
                        '<li class="dropdown-item text-muted">Không có thông báo mới</li>';
                    return;
                } // Populate notifications
                notifications.forEach((notification) => {
                    const listItem = document.createElement("li");
                    listItem.classList.add("dropdown-item");
                    listItem.innerHTML =
                        ` <p class="mb-0">${notification.message}</p> <small class="text-muted">${new Date(notification.created_at).toLocaleString()}</small> `;
                    notificationList.appendChild(listItem);
                });
            } catch (error) {
                console.error("Error fetching notifications:", error);
            }
        } // Fetch notifications when dropdown is shown
        notificationDropdown.addEventListener("show.bs.dropdown", fetchNotifications);
    });
</script>

</html>
