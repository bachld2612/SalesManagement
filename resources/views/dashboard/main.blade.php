@extends('layouts.dashboard')

@section('main-content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-warning">
                        <span class="material-icons">equalizer</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>View</strong></p>
                    <h3 class="card-title">70,340</h3>
                    {{-- @foreach ($userRoles as $role)
                        <tr>
                            <td>{{ $role->role_name }}</td>
                            <td>{{ $role->user_count }}</td>
                        </tr>
                    @endforeach --}}
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons text-info">info</i>
                        <a href="#pablo">See detailed report</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-rose">
                        <span class="material-icons">shopping_cart</span>

                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Orders</strong></p>
                    <h3 class="card-title">102</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">local_offer</i> Product-wise sales
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-success">
                        <span class="material-icons">
                            attach_money
                        </span>

                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Doanh thu các đơn hàng đã thanh toán</strong></p>
                    <h3 class="card-title">{{ $doanhthu }}</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">date_range</i> Weekly sales
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-info">

                        <span class="material-icons">
                            follow_the_signs
                        </span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Account quantity</strong></p>
                    {{-- <h3 class="card-title">+245</h3> --}}
                    @foreach ($countUserRoles as $role)
                        <tr>
                            <td>{{ $role->role_name }}</td>
                            <td>{{ $role->user_count }}</td>
                        </tr>
                    @endforeach
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">update</i> Just Updated
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row ">
        <div class="col-lg-7 col-md-12">
            <div class="card" style="min-height: 485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Danh sách tài khoản mới khởi tạo</h4>
                    <p class="category">7 ngày gần nhất</p>
                </div>
                <div class="card-content table-responsive">
                    <table class="table table-hover">
                        <thead class="text-primary">
                            <tr>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Membership Tier</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topSpenders as $index => $spender)
                                <tr>
                                    <td>{{ $spender->username }}</td>
                                    <td>{{ $spender->fullname }}</td>
                                    <td>{{ $spender->membership_tier }}</td>
                                    <td>{{ $spender->phone_number }}</td>
                                    <td>{{ $spender->address }}</td>
                                    <td>{{ $spender->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-12">
            <div class="card" style="min-height: 485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Top chi tiêu</h4>
                </div>
                <div class="card-content">
                    <div class="streamline">
                        <div class="sl-item sl-primary">
                            @foreach ($topchitieu as $chitieu)
                                <div class="sl-content">
                                    <small class="text-muted"> {{ $chitieu->TotalSpent }} </small>
                                    <p>{{ $chitieu->Fullname }}</p>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
