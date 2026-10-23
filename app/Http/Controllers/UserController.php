<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 1. Tampilkan Daftar User
    public function index()
    {
        // Ambil semua data user, urutkan dari yang terbaru
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    // 2. Tampilkan Form Tambah User
    public function create()
    {
        return view('users.create');
    }

    // 3. Proses Simpan User ke Database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'role' => ['required', 'string'], Pastikan ada input role
            'role' => ['required', 'in:admin,agent,tl,management'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Buat User Baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        // Balik ke halaman list dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // 5. Proses Update Data
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id], // Abaikan email milik sendiri
            'role' => ['required', 'in:admin,agent,tl,management'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password boleh kosong
        ]);

        // Siapkan data yang mau diupdate
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Cek apakah password diisi? Kalau ya, update password baru.
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui!');
    }

    // 6. Hapus User
    public function destroy(User $user)
    {
        // LOGIKA: Cek apakah ID yang login SAMA DENGAN ID user yang mau dihapus
        // auth()->id()  <-- Pakai kurung (Fungsi mengambil ID yang login)
        // $user->id     <-- TANPA kurung (Ambil data ID dari database)

        if (Auth::id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
