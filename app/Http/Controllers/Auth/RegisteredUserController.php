<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required'],
            'phoneNumber' => ['required', 'string', 'max:15'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
        ]);

        $user = User::create([
            'fullname' => $request->name,
            'username' => $request->username,
            'address' => $request->address,
            'phone_number' => $request->phoneNumber,
            'password' => Hash::make($request->password),
            'role_name' => 'customer',
        ]);

        event(new Registered($user));


        return redirect(route('login', absolute: false));
    }
}
