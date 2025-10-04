<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mekanik;
use App\Models\Layanan;
use App\Models\KategoriKendaraan;
use App\Models\LogActivity;  // Pastikan ini diimport
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function home()
    {
        $kategoriList = KategoriKendaraan::where('status', 'aktif')->get();
        $layanans = Layanan::where('status', 'aktif')->get();

        return view('home', compact('kategoriList', 'layanans'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pengguna',
        ]);

        // Log aktivitas
        LogActivity::create([
            'username' => Auth::user()->email ?? 'Guest',
            'activity' => "Mendaftarkan user baru dengan email: {$user->email}",
            'ip_address' => $request->ip(),
        ]);

        Auth::login($user);

        return redirect('/login');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Log aktivitas
            LogActivity::create([
                'username' => Auth::user()->email ?? 'Guest',
                'activity' => "User login dengan email: {$user->email}",
                'ip_address' => $request->ip(),
            ]);

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect('dashboardadmin');
            } else {
                return redirect('home');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log aktivitas
        LogActivity::create([
            'username' => $user->email ?? 'Guest',
            'activity' => "User logout dengan email: {$user->email}",
            'ip_address' => $request->ip(),
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function listUsers()
    {
        $users = User::all();
        return view('users', compact('users'));
    }

    public function createUser()
    {
        return view('create_user');
    }

    public function storeUser(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Log aktivitas
        LogActivity::create([
            'username' => Auth::user()->email ?? 'Guest',
            'activity' => "Menambahkan user baru dengan email: {$user->email}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('edit_user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Log aktivitas
        LogActivity::create([
            'username' => Auth::user()->email ?? 'Guest',
            'activity' => "Mengupdate data user dengan email: {$user->email}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil diupdate.');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        // Log aktivitas
        LogActivity::create([
            'username' => Auth::user()->email ?? 'Guest',
            'activity' => "Menghapus user dengan email: {$user->email}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function mekanik()
    {
        $mekanik = Mekanik::all();
        return view('mekanik', compact('mekanik'));
    }

    public function create()
    {
        return view('create_mekanik');
    }

    public function store(Request $request)
    {
        $mekanik = Mekanik::create($request->all());

        // Log aktivitas
        LogActivity::create([
            'username' => Auth::user()->email ?? 'Guest',
            'activity' => "Menambahkan mekanik baru dengan nama: {$mekanik->nama}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('mekanik.index')->with('success', 'Mekanik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mekanik = Mekanik::findOrFail($id);
        return view('edit_mekanik', compact('mekanik'));
    }

    public function update(Request $request, $id)
    {
        $mekanik = Mekanik::findOrFail($id);
        $mekanik->update($request->all());

        // Log aktivitas
        LogActivity::create([
            'username' => Auth::user()->email ?? 'Guest',
            'activity' => "Mengupdate data mekanik dengan nama: {$mekanik->nama}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('mekanik.index')->with('success', 'Mekanik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mekanik = Mekanik::findOrFail($id);
        $mekanik->delete();

        // Log aktivitas
        LogActivity::create([
            'username' => Auth::user()->email ?? 'Guest',
            'activity' => "Menghapus mekanik dengan nama: {$mekanik->nama}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('mekanik.index')->with('success', 'Mekanik berhasil dihapus.');
    }

    public function dashboardadmin()
    {
        return view('dashboardadmin');
    }
}
