<!DOCTYPE html>
<html>
<head>
    <title>Staff Management</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<h1>Staff Management</h1>

<a href="<?= site_url('users/create') ?>">Add Staff</a>
<a href="<?= site_url('dashboard') ?>">Dashboard</a>

<?php foreach (['success', 'error'] as $type): ?>
    <?php if (session()->getFlashdata($type)): ?>
        <p><?= esc(session()->getFlashdata($type)) ?></p>
    <?php endif; ?>
<?php endforeach; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>Avatar</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($users as $user): ?>
        <tr>
            <td>
                <?php if ($user['avatar']): ?>
                    <img
                        src="<?= base_url('uploads/avatars/' . rawurlencode($user['avatar'])) ?>"
                        width="60"
                        height="60"
                        alt="Staff avatar">
                <?php else: ?>
                    No avatar
                <?php endif; ?>
            </td>

            <td><?= esc($user['username']) ?></td>
            <td><?= esc($user['full_name']) ?></td>

            <td>
                <a href="<?= site_url('users/edit/' . $user['id']) ?>">
                    Edit
                </a>

                <?php if ((int) $user['id'] !== (int) session()->get('user_id')): ?>
                    <form method="post"
                          action="<?= site_url('users/delete/' . $user['id']) ?>">
                        <?= csrf_field() ?>

                        <button type="submit"
                                onclick="return confirm('Delete this staff account?')">
                            Delete
                        </button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>