<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Customers extends BaseController
{
    public function index()
    {
        return view('customers/index', [
            'customers' => (new CustomerModel())->findAll()
        ]);
    }

    public function create()
    {
        return view('customers/form');
    }

    public function store()
    {
        if (!$this->validate([
            'full_name' => 'required|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'phone' => 'permit_empty|max_length[20]'
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        (new CustomerModel())->insert([
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone')
        ]);

        return redirect()->to('/customers')
            ->with('success', 'Customer added successfully!');
    }

    public function edit($id)
    {
        $customer = (new CustomerModel())->find($id);

        if (!$customer) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('customers/form', ['customer' => $customer]);
    }

    public function update($id)
    {
        $model = new CustomerModel();

        if (!$model->find($id)) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (!$this->validate([
            'full_name' => 'required|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'phone' => 'permit_empty|max_length[20]'
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone')
        ]);

        return redirect()->to('/customers')
            ->with('success', 'Customer updated successfully!');
    }

    public function delete($id)
    {
        $model = new CustomerModel();

        $hasSales = db_connect()->table('sales')
            ->where('customer_id', $id)
            ->countAllResults() > 0;

        if ($hasSales) {
            return redirect()->to('/customers')
                ->with('error', 'Cannot delete a customer with sales history.');
        }

        $model->delete($id);

        return redirect()->to('/customers')
            ->with('success', 'Customer deleted successfully!');
    }
}