<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function attempt()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('error', 'Enter your username and password.');
        }

        $model = new UserModel();

        $user = $model
            ->where('username', $this->request->getPost('username'))
            ->first();

        if (!$user || !password_verify(
            $this->request->getPost('password'),
            $user['password']
        )) {
            return redirect()->back()
                ->with('error', 'Invalid login credentials.');
        }

        session()->regenerate();

        session()->set([
            'user_id' => $user['id'],
            'full_name' => $user['full_name'],
            'logged_in' => true
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}