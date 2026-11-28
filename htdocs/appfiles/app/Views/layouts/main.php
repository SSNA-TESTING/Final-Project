<?php
helper(['store', 'url']);
$uri = trim(uri_string(), '/'); // current page, relative to baseURL
$on  = fn(string $p) => str_starts_with($uri, $p) ? 'active' : '';
$cur = fn(string $p) => str_starts_with($uri, $p) ? ' aria-current="page"' : '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($this->renderSection('title')) ?> | <?= STORE_NAME ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/christmas.css') ?>" rel="stylesheet">
</head>
<body>
<div class="snow-layer" aria-hidden="true"></div>

<header class="topbar d-lg-none">
  <button class="btn btn-gold btn-sm" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-label="Open menu" title="Open menu"><i class="fa-solid fa-bars"></i></button>
  <span class="brand-sm"><i class="fa-solid fa-tree"></i> <?= STORE_NAME ?></span>
</header>

<div class="app-shell">
  <aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar">
    <div class="offcanvas-header d-lg-none"><button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button></div>
    <div class="sidebar-inner">
      <a href="<?= site_url('dashboard') ?>" class="brand">
        <i class="fa-solid fa-tree brand-icon"></i>
        <span><?= STORE_NAME ?></span>
      </a>
      <div class="garland" aria-hidden="true"><i class="fa-solid fa-star"></i><i class="fa-solid fa-gift"></i><i class="fa-solid fa-bell"></i><i class="fa-solid fa-snowflake"></i><i class="fa-solid fa-star"></i></div>
      <nav class="nav flex-column">
        <a class="nav-link <?= $on('dashboard') ?>"<?= $cur('dashboard') ?> href="<?= site_url('dashboard') ?>"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a class="nav-link <?= $on('products') ?>"<?= $cur('products') ?> href="<?= site_url('products') ?>"><i class="fa-solid fa-tree"></i> Christmas Products</a>
        <a class="nav-link <?= $on('customers') ?>"<?= $cur('customers') ?> href="<?= site_url('customers') ?>"><i class="fa-solid fa-users"></i> Customers</a>
        <a class="nav-link <?= $on('staff') ?>"<?= $cur('staff') ?> href="<?= site_url('staff') ?>"><i class="fa-solid fa-user-tie"></i> Staff Members</a>
        <a class="nav-link <?= $on('sales/create') ?>"<?= $cur('sales/create') ?> href="<?= site_url('sales/create') ?>"><i class="fa-solid fa-cart-shopping"></i> Record Holiday Sale</a>
        <a class="nav-link <?= $on('sales/history') ?>"<?= $cur('sales/history') ?> href="<?= site_url('sales/history') ?>"><i class="fa-solid fa-snowflake"></i> Holiday Sales History</a>
        <a class="nav-link logout" href="<?= site_url('logout') ?>"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </nav>
      <div class="sidebar-user"><i class="fa-solid fa-circle-user"></i> <?= esc(session('user_name')) ?></div>
    </div>
  </aside>

  <div class="main-col">
    <main class="content">
      <div class="content-inner">
        <?= view('layouts/flash') ?>
        <?= $this->renderSection('content') ?>
      </div>
    </main>
    <footer class="site-footer">
      <div><i class="fa-solid fa-tree"></i> <?= STORE_NAME ?> | Christmas Trees and Decorations</div>
      <small>Making every Christmas celebration brighter. <i class="fa-solid fa-star"></i></small>
    </footer>
  </div>
</div>

<!-- Sales History Details Modal -->
<div class="modal fade" id="saleModal" tabindex="-1" aria-labelledby="saleModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title fs-5" id="saleModalTitle">
          <i class="fa-solid fa-gift text-red"></i> Sale <span data-f="id"></span>
        </h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <dl class="detail-list mb-0">
          <dt>Product</dt><dd data-f="product"></dd>
          <dt>Customer</dt><dd data-f="customer"></dd>
          <dt>Staff Member</dt><dd data-f="staff"></dd>
          <dt>Quantity</dt><dd data-f="qty"></dd>
          <dt>Unit Price</dt><dd data-f="unit"></dd>
          <dt>Total Price</dt><dd data-f="total" class="fw-bold text-red"></dd>
          <dt>Date &amp; Time</dt><dd data-f="date"></dd>
        </dl>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark-green" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Shared delete-confirmation dialog -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title fs-5" id="confirmTitle">
          <i class="fa-solid fa-triangle-exclamation text-red"></i> Please confirm
        </h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="confirmText"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark-green" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-red" id="confirmYes">
          <i class="fa-solid fa-trash"></i> Yes, delete
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
