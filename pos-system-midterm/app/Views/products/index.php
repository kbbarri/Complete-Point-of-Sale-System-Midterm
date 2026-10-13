<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<h1>Product Management</h1>

<a href="<?= site_url('products/create') ?>">Add Product</a>
<a href="<?= site_url('dashboard') ?>">Dashboard</a>

<?php foreach (['success', 'error'] as $type): ?>
    <?php if (session()->getFlashdata($type)): ?>
        <p><?= esc(session()->getFlashdata($type)) ?></p>
    <?php endif; ?>
<?php endforeach; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($products as $product): ?>
        <tr>
            <td>
                <?php if ($product['image']): ?>
                    <img src="<?= base_url('uploads/products/' . rawurlencode($product['image'])) ?>"
                         width="60" alt="Product">
                <?php endif; ?>
            </td>
            <td><?= esc($product['name']) ?></td>
            <td>₱<?= number_format($product['price'], 2) ?></td>
            <td><?= esc($product['stock_quantity']) ?></td>
            <td>
                <a href="<?= site_url('products/edit/' . $product['id']) ?>">
                    Edit
                </a>

                <form method="post"
                      action="<?= site_url('products/delete/' . $product['id']) ?>">
                    <?= csrf_field() ?>
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>