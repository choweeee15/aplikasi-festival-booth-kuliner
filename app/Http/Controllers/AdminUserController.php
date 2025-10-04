<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // public function index()
    // {
    //     // kalau mau pagination: ->paginate(10)
    //     $users = User::orderByDesc('id')->get();
    //     return view('admin_users_index', compact('users'));
    // }

    public function index(Request $request)
{
    $perPage = (int) $request->get('per_page', 10);
    $q       = trim($request->get('q', ''));
    $role    = $request->get('role', '');

    $users = \App\Models\User::query()
        ->when($q !== '', function($qr) use ($q){
            $qr->where(function($w) use ($q){
                $w->where('name','like',"%$q%")
                  ->orWhere('email','like',"%$q%");
            });
        })
        ->when($role !== '', fn($qr) => $qr->where('role',$role))
        ->orderByDesc('id')
        ->paginate($perPage)
        ->withQueryString();

    return view('admin_users_index', compact('users'));
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:100'],
            'email'    => ['required','email','max:191','unique:users,email'],
            'password' => ['required','string','min:6'],
            'role'     => ['required', Rule::in(['user','admin'])],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('user.index')->with('success','Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user  = User::findOrFail($id);

        $data = $request->validate([
            'name'     => ['required','string','max:100'],
            'email'    => ['required','email','max:191', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['nullable','string','min:6'],
            'role'     => ['required', Rule::in(['user','admin'])],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success','Pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // opsional: cegah hapus diri sendiri
        // if (auth()->id() === $user->id) {
        //     return back()->with('error','Tidak bisa menghapus akun sendiri.');
        // }

        $user->delete();

        return redirect()->route('user.index')->with('success','Pengguna berhasil dihapus.');
    }
}
