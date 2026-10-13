<?php
$isEdit = isset($product);
$action = $isEdit
    ? site_url('products/update/' . $product['id'])
    : site_url('products/store');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Form</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<h1><?= $isEdit ? 'Edit Product' : 'Add Product' ?></h1>

<?php foreach (session()->getFlashdata('errors') ?? [] as $error): ?>
    <p><?= esc($error) ?></p>
<?php endforeach; ?>

<form method="post" action="<?= $action ?>"
      enctype="multipart/form-data">

    <?= csrf_field() ?>

    <p>
        <label>Product Name</label>
        <input type="text" name="name" required
               value="<?= esc(old('name', $product['name'] ?? '')) ?>">
    </p>

    <p>
        <label>Price</label>
        <input type="number" name="price" step="0.01" min="0" required
               value="<?= esc(old('price', $product['price'] ?? '')) ?>">
    </p>

    <p>
        <label>Stock Quantity</label>
        <input type="number" name="stock_quantity" min="0" required
               value="<?= esc(old('stock_quantity', $product['stock_quantity'] ?? 0)) ?>">
    </p>

    <p>
        <label>Product Image</label>
        <input type="file" name="image" accept="image/*">
    </p>

    <button type="submit">Save Product</button>
</form>

<a href="<?= site_url('products') ?>">Back to Products</a>

</body>
</html>