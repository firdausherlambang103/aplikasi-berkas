<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RiwayatBerkas;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // --- BAGIAN MANAJEMEN USER ---

    public function indexUser()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil ditambahkan');
    }

    public function destroyUser($id)
    {
        if($id == auth()->user()->id){
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        
        User::destroy($id);
        return back()->with('success', 'User berhasil dihapus');
    }

    // --- BAGIAN RIWAYAT / AUDIT LOG ---

    public function indexRiwayat()
    {
        // Ambil riwayat terbaru dengan data user dan berkas
        $riwayats = RiwayatBerkas::with(['user', 'berkas'])
                        ->latest()
                        ->paginate(15);

        return view('admin.riwayat.index', compact('riwayats'));
    }
}