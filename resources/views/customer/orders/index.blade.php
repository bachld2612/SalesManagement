@extends('layouts.app')
@section('content')

<h1 class="text-center text-primary">Đơn hàng</h1>
@if(session('success'))
<div class="alert alert-success">
  {{ session('success') }}
</div>
@endif
<table class="table">
    <thead>
        <tr>
            <th scope="col">STT</th>
            <th scope="col">Địa chỉ</th>
            <th scope="col">Giá</th>
            <th scope="col">Trạng thái</th>
            <th scope="col">Ngày mua</th>
            <th scope="col">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        @if ($order->user_id == auth()->id())
        <tr>
            <td>{{$i++}}</td>
            <td>{{$order->address}}</td>
            <td>{{$order->order_price}} VNĐ</td>
            <td>
                @if ($order->state == 0)
                Đang chuẩn bị
                @elseif ($order->state == 1)
                Đang giao
                @elseif ($order->state == 2)
                Đã hoàn thành
                @elseif ($order->state == 3)
                Đã huỷ
                @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($order->purchase_date)->format('d/m/Y') }}</td>
            <td>
                <a href="{{route('customer.orders.show', $order->order_id)}}" class="btn btn-primary">Chi tiết</a>
                @if ($order->state == 0)
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Huỷ
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Huỷ đơn hàng</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Bạn có chắc chắn muốn huỷ đơn hàng không?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                                <form method="post" action="{{route('customer.orders.destroy', $order->order_id)}}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>
{{$orders->links("pagination::bootstrap-5")}}
@endsection