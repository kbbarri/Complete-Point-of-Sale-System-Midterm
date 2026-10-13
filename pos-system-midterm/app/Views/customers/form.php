<?php
$isEdit = isset($customer);

$action = $isEdit
    ? site_url('customers/update/' . $customer['id'])
    : site_url('customers/store');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Form</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<h1><?= $isEdit ? 'Edit Customer' : 'Add Customer' ?></h1>

<?php foreach (session()->getFlashdata('errors') ?? [] as $error): ?>
    <p><?= esc($error) ?></p>
<?php endforeach; ?>

<form method="post" action="<?= $action ?>">
    <?= csrf_field() ?>

    <p>
        <label>Full Name</label>
        <input type="text" name="full_name" required
               value="<?= esc(old('full_name', $customer['full_name'] ?? '')) ?>">
    </p>

    <p>
        <label>Email</label>
        <input type="email" name="email" required
               value="<?= esc(old('email', $customer['email'] ?? '')) ?>">
    </p>

    <p>
        <label>Phone</label>
        <input type="text" name="phone"
               value="<?= esc(old('phone', $customer['phone'] ?? '')) ?>">
    </p>

    <button type="submit">Save Customer</button>
</form>

<a href="<?= site_url('customers') ?>">Back to Customers</a>

</body>
</html>