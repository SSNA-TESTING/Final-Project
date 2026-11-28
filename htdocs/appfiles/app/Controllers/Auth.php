<?php
namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('user_id')) return redirect()->to('/dashboard');
        return view('auth/login');
    }

    public function attempt()
    {
        $user = (new UserModel())->where('username', trim((string) $this->request->getPost('username')))->first();
        if (! $user || ! password_verify((string) $this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid username or password. Please try again.');
        }
        session()->regenerate();
        session()->set(['user_id' => $user['id'], 'user_name' => $user['full_name'], 'user_avatar' => $user['avatar']]);
        return redirect()->to('/dashboard')->with('success', 'Welcome to ' . STORE_NAME . ', ' . $user['full_name'] . '!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been signed out. Happy holidays!');
    }
}
