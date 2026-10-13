<!DOCTYPE html>
<html>
<head>
    <title>POS Login</title>
    
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <h1>Point-of-Sale System</h1>
    <h2>Staff Login</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <p><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= site_url('login') ?>">
        <?= csrf_field() ?>

        <input type="text" name="username"
               placeholder="Username" required>

        <input type="password" name="password"
               placeholder="Password" required>

        <button type="submit">Login</button>
    </form>
</body>
</html>