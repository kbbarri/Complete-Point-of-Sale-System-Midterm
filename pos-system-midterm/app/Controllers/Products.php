<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    public function index()
    {
        $model = new ProductModel();

        return view('products/index', [
            'products' => $model->findAll()
        ]);
    }

    public function create()
    {
        return view('products/form');
    }

    public function store()
    {
        $rules = [
            'name' => 'required|max_length[100]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'image' => 'permit_empty|is_image[image]|max_size[image,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity')
        ];

        $image = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $name = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $name);
            $data['image'] = $name;
        }

        (new ProductModel())->insert($data);

        return redirect()->to('/products')
            ->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        $product = (new ProductModel())->find($id);

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('products/form', ['product' => $product]);
    }

    public function update($id)
    {
        $model = new ProductModel();

        if (!$model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'name' => 'required|max_length[100]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'image' => 'permit_empty|is_image[image]|max_size[image,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity')
        ];

        $image = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $name = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $name);
            $data['image'] = $name;
        }

        $model->update($id, $data);

        return redirect()->to('/products')
            ->with('success', 'Product updated successfully!');
    }

    public function delete($id)
    {
        $model = new ProductModel();

        // Keep products referenced by existing sales.
        $hasSales = db_connect()->table('sales')
            ->where('product_id', $id)
            ->countAllResults() > 0;

        if ($hasSales) {
            return redirect()->to('/products')
                ->with('error', 'Cannot delete a product with sales history.');
        }

        $model->delete($id);

        return redirect()->to('/products')
            ->with('success', 'Product deleted successfully!');
    }
}