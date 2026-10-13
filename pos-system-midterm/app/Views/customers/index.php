<!DOCTYPE html>
<html>
<head>
    <title>Customer Management</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<h1>Customer Management</h1>

<a href="<?= site_url('customers/create') ?>">Add Customer</a>
<a href="<?= site_url('dashboard') ?>">Dashboard</a>

<?php foreach (['success', 'error'] as $type): ?>
    <?php if (session()->getFlashdata($type)): ?>
        <p><?= esc(session()->getFlashdata($type)) ?></p>
    <?php endif; ?>
<?php endforeach; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($customers as $customer): ?>
        <tr>
            <td><?= esc($customer['full_name']) ?></td>
            <td><?= esc($customer['email']) ?></td>
            <td><?= esc($customer['phone']) ?></td>
            <td>
                <a href="<?= site_url('customers/edit/' . $customer['id']) ?>">
                    Edit
                </a>

                <form method="post"
                      action="<?= site_url('customers/delete/' . $customer['id']) ?>">
                    <?= csrf_field() ?>
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>