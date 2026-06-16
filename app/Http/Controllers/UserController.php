<?php

namespace App\Http\Controllers;

use App\Models\{User, Role};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('nama_lengkap')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:50|unique:users,username',
            'password'     => 'required|string|min:6|confirmed',
            'id_role'      => 'required|exists:roles,id_role',
            'status'       => 'required|in:aktif,nonaktif',
        ], [
            'username.unique'            => 'Username sudah digunakan.',
            'password.min'               => 'Password minimal 6 karakter.',
            'password.confirmed'         => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'id_role'      => $request->id_role,
            'status'       => $request->status,
        ]);

        return back()->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:50|unique:users,username,' . $id . ',id_user',
            'id_role'      => 'required|exists:roles,id_role',
            'status'       => 'required|in:aktif,nonaktif',
            'password'     => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only('nama_lengkap', 'username', 'id_role', 'status');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($id == auth()->id() && $request->status === 'nonaktif') {
            return back()->with('error', 'Tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update($data);

        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        if ($id == auth()->id()) {
            return response()->json(['error' => 'Tidak dapat menonaktifkan akun sendiri.'], 422);
        }

        $user = User::findOrFail($id);
        $user->status = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        return response()->json(['status' => $user->status, 'message' => 'Status berhasil diubah.']);
    }
}
