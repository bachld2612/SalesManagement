<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashBoard extends Controller
{
    //
    public function displayResults()
    {
        $countUserRoles = DB::select('SELECT * FROM fn_CountUsersByRole()');

        $topSpenders = DB::select('SELECT * FROM vw_RecentUsers');

        $topchitieu = DB::select('SELECT * FROM dbo.GetTop10Spenders()');

        $doanhthuResult = DB::select('SELECT COALESCE(dbo.getPaidMoney(), 0) AS total_paid_money');
        $doanhthu = $doanhthuResult[0]->total_paid_money ?? 0;


        return view('dashboard/main', ['countUserRoles' => $countUserRoles, 'topSpenders' => $topSpenders, 'topchitieu' => $topchitieu, 'doanhthu' => $doanhthu]);
    }


    public function displayCustomer()
    {
        $customers = DB::select('SELECT * FROM users WHERE role_name = ?', ['customer']);
        return view('dashboard/customer/index', ['users' => $customers]);
    }

    public function displayEmployees()
    {
        $employees = DB::select('SELECT * FROM users WHERE role_name = ?', ['staff']);
        return view('dashboard/employees/index', ['users' => $employees]);
    }

    public function addCustomer()
    {

        return view('dashboard/customer/addCustomer');
    }

    public function addEmployees()
    {

        return view('dashboard/employees/add');
    }

    public function editCustomer($id)
    {
        $user = User::findOrFail($id);
        return view('dashboard/customer/edit', ['user' => $user]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'fullname' => 'nullable',
            'address' => 'nullable',
            'phone_number' => 'nullable'
        ]);

        DB::insert('INSERT INTO users (username, password, fullname, address, phone_number, role_name) VALUES (?, ?, ?, ?, ?, ?)', [
            $request->username,
            bcrypt($request->password), // Bcrypt mã hóa mật khẩu để bảo mật
            $request->fullname ?? null,
            $request->address ?? null,
            $request->phone_number ?? null,
            'customer'
        ]);

        return redirect()->route('dashboard.customer');
    }

    public function storeEmployees(Request $request)
    {
        // Validate các trường thông tin từ form
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'fullname' => 'nullable',
            'address' => 'nullable',
            'position' => 'required', // Bổ sung validate cho "chức vụ"
            'phone_number' => 'nullable'
        ]);

        // Thực hiện chèn dữ liệu vào bảng users
        DB::insert('INSERT INTO users (username, password, fullname, address, phone_number, role_name) VALUES (?, ?, ?, ?, ?, ?)', [
            $request->username,
            bcrypt($request->password), // Bcrypt mã hóa mật khẩu để bảo mật
            $request->fullname ?? null,
            $request->address ?? null,
            $request->phone_number ?? null,
            $request->position // Sử dụng giá trị từ trường select "chức vụ"
        ]);

        return redirect()->route('dashboard.employees');
    }


    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu
        $request->validate([
            'username' => 'required',
            'password' => 'nullable',
            'fullname' => 'nullable',
            'address' => 'nullable',
            'phone_number' => 'nullable'
        ]);

        // Lấy người dùng cần chỉnh sửa từ cơ sở dữ liệu
        $user = User::findOrFail($id);

        // Cập nhật thông tin người dùng
        $user->username = $request->username;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password); // Mã hóa mật khẩu mới nếu có thay đổi
        }

        $user->fullname = $request->fullname ?? $user->fullname;
        $user->address = $request->address ?? $user->address;
        $user->phone_number = $request->phone_number ?? $user->phone_number;

        // Lưu lại thay đổi vào cơ sở dữ liệu
        $user->save();

        // Chuyển hướng sau khi cập nhật thành công
        return redirect()->route('dashboard.customer')->with('success', 'Người dùng đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        // Lấy người dùng cần xóa từ cơ sở dữ liệu
        $user = User::findOrFail($id);

        // Xóa người dùng
        $user->delete();

        // Chuyển hướng sau khi xóa thành công
        return redirect()->route('dashboard.customer')->with('success', 'Người dùng đã được xóa thành công!');
    }
}
