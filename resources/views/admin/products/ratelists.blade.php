
@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách đánh giá sản phẩm</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Sản Phẩm</th>
                <th>Tên Sản Phẩm</th>
                <th>Trung Bình Đánh Giá</th>
                <th>Tổng Số Đánh Giá</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ratings as $rating)
            <tr>
                <td>{{ $rating->product_id }}</td>
                <td>{{ $rating->product_name }}</td>
                <td>{{ number_format($rating->average_rating, 2) }}</td>
                <td>{{ $rating->total_ratings }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

