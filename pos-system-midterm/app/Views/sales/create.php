<!DOCTYPE html>
<html>
<head>
    <title>Record Sale</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<h1>Record Sale</h1>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red">
        <?= esc(session()->getFlashdata('error')) ?>
    </p>
<?php endif; ?>

<form method="post" action="<?= site_url('sales/store') ?>">
    <?= csrf_field() ?>

    <p>
        <label>Product</label>
        <select name="product_id" required>
            <?php foreach ($products as $product): ?>
                <option value="<?= esc($product['id']) ?>">
                    <?= esc($product['name']) ?>
                    - ₱<?= number_format($product['price'], 2) ?>
                    (Stock: <?= esc($product['stock_quantity']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label>Customer (Optional)</label>
        <select name="customer_id">
            <option value="">Walk-in Customer</option>

            <?php foreach ($customers as $customer): ?>
                <option value="<?= esc($customer['id']) ?>">
                    <?= esc($customer['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label>Quantity</label>
        <input type="number" name="quantity"
               min="1" required>
    </p>

    <button type="submit">Record Sale</button>
</form>

<p>
    <a href="<?= site_url('dashboard') ?>">Back to Dashboard</a>
</p>

</body>
</html>