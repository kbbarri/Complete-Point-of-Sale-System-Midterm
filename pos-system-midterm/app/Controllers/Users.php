<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Users extends BaseController
{
    public function index()
    {
        return view('users/index', [
            'users' => (new UserModel())->findAll()
        ]);
    }

    public function create()
    {
        return view('users/form');
    }

    private function saveAvatar(array &$data)
    {
        $avatar = $this->request->getFile('avatar');

        if ($avatar && $avatar->isValid() && !$avatar->hasMoved()) {
            $folder = FCPATH . 'uploads/avatars';

            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }

            $filename = $avatar->getRandomName();
            $avatar->move($folder, $filename);
            $data['avatar'] = $filename;
        }
    }

    public function store()
    {
        $rules = [
            'username' => 'required|max_length[50]|alpha_dash|is_unique[users.username]',
            'full_name' => 'required|max_length[100]',
            'password' => 'required|min_length[8]',
            'avatar' => 'permit_empty|is_image[avatar]|mime_in[avatar,image/jpeg,image/png,image/webp]|max_size[avatar,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            )
        ];

        $this->saveAvatar($data);

        (new UserModel())->insert($data);

        return redirect()->to('/users')
            ->with('success', 'Staff account created!');
    }

    public function edit($id)
    {
        $user = (new UserModel())->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('users/form', ['user' => $user]);
    }

    public function update($id)
    {
        $model = new UserModel();
        $user = $model->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'username' => "required|max_length[50]|alpha_dash|is_unique[users.username,id,{$id}]",
            'full_name' => 'required|max_length[100]',
            'password' => 'permit_empty|min_length[8]',
            'avatar' => 'permit_empty|is_image[avatar]|mime_in[avatar,image/jpeg,image/png,image/webp]|max_size[avatar,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name')
        ];

        $password = $this->request->getPost('password');

        if (!empty($password)) {
            $data['password'] = password_hash(
                $password,
                PASSWORD_DEFAULT
            );
        }

        $this->saveAvatar($data);
        $model->update($id, $data);

        if ((int) session()->get('user_id') === (int) $id) {
            session()->set('full_name', $data['full_name']);
        }

        return redirect()->to('/users')
            ->with('success', 'Staff account updated!');
    }

    public function delete($id)
    {
        if ((int) session()->get('user_id') === (int) $id) {
            return redirect()->to('/users')
                ->with('error', 'You cannot delete your own account.');
        }

        $hasSales = db_connect()->table('sales')
            ->where('sold_by', $id)
            ->countAllResults() > 0;

        if ($hasSales) {
            return redirect()->to('/users')
                ->with('error', 'Cannot delete staff with sales history.');
        }

        (new UserModel())->delete($id);

        return redirect()->to('/users')
            ->with('success', 'Staff account deleted!');
    }
}