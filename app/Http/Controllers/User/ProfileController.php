<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // PENTING: Untuk enkripsi password baru

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.app.profile');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);

        $path = $request->file('avatar')->store('asset/avatar', 'public');

        Auth::user()->resident->update(['avatar' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    // FUNGSI BARU: Ganti Kata Sandi Dummy
    public function updatePasswordDummy(Request $request)
    {
        // 1. Validasi simpel
        $request->validateWithBag('updatePassword', [
            'old_password' => ['required'], 
            'new_password' => ['required', 'min:8'], 
        ]);

        // 2. Langsung update password baru
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // 3. Kembali dengan pesan sukses
        return back()->with('status', 'Kata sandi berhasil diperbarui!');
    }

    public function notifications()
    {
    // Mengambil laporan milik user login, diurutkan dari yang terbaru
        $reports = Auth::user()->resident->reports()->with('reportStatuses')->latest()->get();

        return view('pages.app.notification', compact('reports'));
    }
}