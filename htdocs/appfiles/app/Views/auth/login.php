<?php helper('store'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | <?= STORE_NAME ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/christmas.css') ?>" rel="stylesheet">
</head>
<body class="login-body">
<div class="snow-layer" aria-hidden="true"></div>
<i class="fa-solid fa-star deco deco-1" aria-hidden="true"></i>
<i class="fa-solid fa-bell deco deco-2" aria-hidden="true"></i>
<i class="fa-solid fa-gift deco deco-3" aria-hidden="true"></i>
<i class="fa-solid fa-snowflake deco deco-4" aria-hidden="true"></i>

<div class="login-wrap">
  <div class="login-card">
    <div class="garland" aria-hidden="true"><i class="fa-solid fa-star"></i><i class="fa-solid fa-gift"></i><i class="fa-solid fa-bell"></i><i class="fa-solid fa-snowflake"></i><i class="fa-solid fa-star"></i></div>
    <div class="text-center">
      <div class="login-icon"><i class="fa-solid fa-tree"></i></div>
      <h1 class="login-title"><?= STORE_NAME ?></h1>
      <h2 class="login-welcome">Welcome to <?= STORE_NAME ?></h2>
      <p class="text-muted">Manage your Christmas trees and holiday decorations with ease.</p>
    </div>
    <?= view('layouts/flash') ?>
    <form method="post" action="<?= site_url('login') ?>">
      <?= csrf_field() ?>
      <label class="form-label" for="username">Username</label>
      <div class="input-group mb-3">
        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
        <input class="form-control" id="username" name="username" value="<?= esc(old('username')) ?>" required autofocus autocomplete="username">
      </div>
      <label class="form-label" for="password">Password</label>
      <div class="input-group mb-4">
        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
      </div>
      <button class="btn btn-green w-100"><i class="fa-solid fa-gift"></i> Sign In</button>
    </form>
    <p class="text-center small text-muted mt-4 mb-0">Making every Christmas celebration brighter.</p>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
