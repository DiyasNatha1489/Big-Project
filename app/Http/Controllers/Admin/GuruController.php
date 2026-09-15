<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuruController extends Controller
{
    public function index()
    {
        $guru = User::where('role', 'wali_kelas')->get();
        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
        ]);

        $passwordAwal = Str::random(8);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($passwordAwal),
            'role' => 'wali_kelas',
            'wajib_ganti_password' => true,
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', "Akun guru berhasil dibuat. Password awal: $passwordAwal")
            ->with('password_awal', $passwordAwal);
    }

    public function edit(User $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, User $guru)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$guru->id,
        ]);

        $guru->update($request->only('name', 'email'));

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(User $guru)
    {
        $guru->delete();
        return redirect()->route('admin.guru.index')->with('success', 'Akun guru berhasil dihapus.');
    }
}