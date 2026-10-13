<!DOCTYPE html>
<html>
<head>
    <title>Sales History</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<h1>Sales History</h1>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color:green">
        <?= esc(session()->getFlashdata('success')) ?>
    </p>
<?php endif; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>Product</th>
        <th>Customer</th>
        <th>Staff</th>
        <th>Quantity</th>
        <th>Total Price</th>
        <th>Date</th>
    </tr>

    <?php foreach ($sales as $sale): ?>
        <tr>
            <td><?= esc($sale['product_name']) ?></td>
            <td><?= esc($sale['customer_name'] ?? 'Walk-in') ?></td>
            <td><?= esc($sale['staff_name']) ?></td>
            <td><?= esc($sale['quantity']) ?></td>
            <td>₱<?= number_format($sale['total_price'], 2) ?></td>
            <td><?= esc($sale['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p>
    <a href="<?= site_url('sales/create') ?>">Record Another Sale</a>
</p>

<a href="<?= site_url('dashboard') ?>">Back to Dashboard</a>

</body>
</html>