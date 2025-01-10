@extends('layouts.dashboard')

@section('main-content')
    {{-- <a href="{{ route('tasks.create') }}" class="btn btn-primary">Thêm mới</a> --}}

    <div class="col-lg-12 col-md-12">
        <a href="{{ route('dashboard.customer.create') }}" class="btn btn-primary">Thêm mới</a>

        <div class="card" style="min-height: 485px">
            <div class="card-header card-header-text">
                <h4 class="card-title">Employees Stats</h4>
                <p class="category">New employees on 15th December, 2016</p>
            </div>
            <div class="card-content table-responsive">
                <table class="table table-hover">
                    <thead class="text-primary">
                        <tr>
                            <th class="col-1">STT</th>
                            <th class="col-2">User Name</th>
                            <th class="col-2">Full Name</th>
                            <th class="col-2">Address</th>
                            <th class="col-2">Membership Tier</th>
                            <th class="col-2">Phone Number </th>
                            <th class="col-1">Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->fullname }}</td>
                                <td>{{ $user->address }}</td>
                                <td>{{ $user->membership_tier }}</td>
                                <td>{{ $user->phone_number }}</td>



                                <td>
                                    {{-- <div class="d-flex align-items-center" style="height: 100%;"> <a
                                            href="{{ route('tasks.show', $user) }}" class="mx-2"> <i
                                                class="bi bi-eye-fill"></i>
                                        </a> <a href="{{ route('tasks.edit', $task) }}" class="mx-2"> <i
                                                class="bi bi-pencil-fill"></i> </a>
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $task->id }}" class="mx-2">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </div> --}}
                                    <div class="d-flex align-items-center" style="height: 100%;">
                                        <a href="{{ route('users.edit', $user->id) }}" class="mx-2"> <i
                                                class="bi bi-pencil-fill">sửa</i> </a>
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $user->id }}" class="mx-2">
                                            <i class="bi bi-trash-fill">
                                                xóa
                                            </i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1"
                                aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Xác nhận xóa
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bạn có chắc chắn muốn xóa nhiệm vụ: <strong>{{ $user->username }}</strong>?
                                            </p>

                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('users.delete', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Hủy</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
