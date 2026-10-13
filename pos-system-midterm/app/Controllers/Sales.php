<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CustomerModel;
use Config\Database;

class Sales extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        $sales = $db->table('sales')
            ->select('sales.*, products.name AS product_name,
                customers.full_name AS customer_name,
                users.full_name AS staff_name')
            ->join('products', 'products.id = sales.product_id')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->join('users', 'users.id = sales.sold_by')
            ->orderBy('sales.created_at', 'DESC')
            ->get()->getResultArray();

        return view('sales/index', ['sales' => $sales]);
    }

    public function create()
    {
        return view('sales/create', [
            'products' => (new ProductModel())->findAll(),
            'customers' => (new CustomerModel())->findAll()
        ]);
    }

    public function store()
    {
        if (!$this->validate([
            'product_id' => 'required|is_natural_no_zero',
            'quantity' => 'required|is_natural_no_zero',
            'customer_id' => 'permit_empty|is_natural_no_zero'
        ])) {
            return redirect()->back()->withInput()
                ->with('error', 'Invalid sale details.');
        }

        $db = Database::connect();
        $productId = (int) $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity');
        $customerId = $this->request->getPost('customer_id');

        $db->transBegin();

        try {
            $product = $db->query(
                'SELECT * FROM products WHERE id = ? FOR UPDATE',
                [$productId]
            )->getRowArray();

            if (!$product || $quantity > (int) $product['stock_quantity']) {
                $db->transRollback();

                return redirect()->back()->withInput()
                    ->with('error', 'Insufficient stock or product not found.');
            }

            if ($customerId && !(new CustomerModel())->find($customerId)) {
                $db->transRollback();

                return redirect()->back()->withInput()
                    ->with('error', 'Customer not found.');
            }

            $db->table('sales')->insert([
                'product_id' => $productId,
                'customer_id' => $customerId ?: null,
                'sold_by' => session()->get('user_id'),
                'quantity' => $quantity,
                'total_price' => $product['price'] * $quantity,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $db->table('products')
                ->where('id', $productId)
                ->update([
                    'stock_quantity' =>
                        (int) $product['stock_quantity'] - $quantity
                ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaction failed.');
            }

            $db->transCommit();

            return redirect()->to('/sales')
                ->with('success', 'Sale recorded successfully!');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', $e->getMessage());

            return redirect()->back()
                ->with('error', 'Unable to record sale.');
        }
    }
}