<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('username', $request->username)->with('role')->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Username tidak terdaftar.'])->withInput();
        }

        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password tidak valid.'])->withInput();
        }

        if ($user->status === 'nonaktif') {
            return back()->withErrors(['username' => 'Akun Anda telah dinonaktifkan. Hubungi Admin.'])->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    private function redirectByRole(User $user)
    {
        return $user->isAdmin()
            ? redirect()->route('dashboard.admin')
            : redirect()->route('transaksi.index');
    }
}
