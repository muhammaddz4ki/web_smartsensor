<?php

namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
{
    // Hanya tampilkan form login
    return view('auth/login');
}

    public function register()
    {
        return view('auth/register');
    }

    public function attemptRegister()
{
    $userModel = new UserModel();

    $username = $this->request->getPost('username');
    $email    = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    // Cek apakah username sudah digunakan
    if ($userModel->where('username', $username)->first()) {
        return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
    }

    // Cek apakah email sudah digunakan
    if ($userModel->where('email', $email)->first()) {
        return redirect()->back()->withInput()->with('error', 'Email sudah digunakan.');
    }

    // Simpan user baru
    $data = [
        'username' => $username,
        'email'    => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
    ];

    $userModel->save($data);
    return redirect()->to('/login');
}


    public function attemptLogin()
{
    $userModel = new UserModel();
    $loginInput = $this->request->getPost('login');
    $password = $this->request->getPost('password');

    $user = $userModel
        ->where('username', $loginInput)
        ->orWhere('email', $loginInput)
        ->first();

    if (!$user) {
        return redirect()->back()->withInput()->with('error', 'Username atau Email tidak ditemukan.');
    }

    if (!password_verify($password, $user['password'])) {
        return redirect()->back()->withInput()->with('error', 'Password salah.');
    }

    // Simpan username dan id user di session
    session()->set([
        'user_id' => $user['id'],
        'username' => $user['username'] // Tambahkan ini
    ]);
    
    return redirect()->to('/dashboard');
}
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
