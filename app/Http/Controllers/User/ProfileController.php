<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
