<?php
$isEdit = isset($user);

$action = $isEdit
    ? site_url('users/update/' . $user['id'])
    : site_url('users/store');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Form</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<h1><?= $isEdit ? 'Edit Staff' : 'Add Staff' ?></h1>

<?php foreach (session()->getFlashdata('errors') ?? [] as $error): ?>
    <p><?= esc($error) ?></p>
<?php endforeach; ?>

<form method="post"
      action="<?= $action ?>"
      enctype="multipart/form-data">

    <?= csrf_field() ?>

    <p>
        <label>Username</label>
        <input type="text"
               name="username"
               required
               value="<?= esc(old('username', $user['username'] ?? '')) ?>">
    </p>

    <p>
        <label>Full Name</label>
        <input type="text"
               name="full_name"
               required
               value="<?= esc(old('full_name', $user['full_name'] ?? '')) ?>">
    </p>

    <p>
        <label>Password</label>
        <input type="password"
               name="password"
               minlength="8"
               <?= $isEdit ? '' : 'required' ?>>

        <?php if ($isEdit): ?>
            <small>Leave blank to keep the current password.</small>
        <?php endif; ?>
    </p>

    <p>
        <label>Avatar</label>
        <input type="file"
               name="avatar"
               accept="image/jpeg,image/png,image/webp">
    </p>

    <?php if ($isEdit && !empty($user['avatar'])): ?>
        <img
            src="<?= base_url('uploads/avatars/' . rawurlencode($user['avatar'])) ?>"
            width="80"
            alt="Current avatar">
    <?php endif; ?>

    <p>
        <button type="submit">Save Staff</button>
    </p>
</form>

<a href="<?= site_url('users') ?>">Back to Staff</a>

</body>
</html>