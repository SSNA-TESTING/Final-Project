<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
$cards = [
  ['Total Christmas Products', number_format($totalProducts), 'fa-tree', 'green'],
  ['Total Available Stock', number_format($totalStock), 'fa-gift', 'red'],
  ['Registered Customers', number_format($customers), 'fa-users', 'gold'],
  ['Staff Members', number_format($staff), 'fa-user-tie', 'green'],
  ['Total Holiday Sales', number_format($totalSales), 'fa-bag-shopping', 'red'],
  ['Total Sales Revenue', peso($revenue), 'fa-coins', 'gold'],
];
?>
<div class="page-head">
  <div><h1><i class="fa-solid fa-snowflake"></i> Holiday Dashboard</h1>
  <p class="text-muted mb-0">Season’s greetings, <?= esc(session('user_name')) ?>! Here is how <?= STORE_NAME ?> is doing today.</p></div>
  <a href="<?= site_url('sales/create') ?>" class="btn btn-green"><i class="fa-solid fa-cart-shopping"></i> Record Holiday Sale</a>
</div>

<?= view('partials/stats', ['cards' => $cards]) ?>

<div class="row g-4">
  <div class="col-12 col-xl-5">
    <div class="panel">
      <h2 class="panel-title"><i class="fa-solid fa-star"></i> Low-Stock Christmas Items</h2>
      <?php if (! $lowStock): ?>
        <?= view('partials/empty', ['icon' => 'fa-tree', 'message' => 'All Christmas items are well stocked.']) ?>
      <?php else: ?>
      <ul class="list-clean">
        <?php foreach ($lowStock as $p): ?>
        <li><span><?= esc($p['name']) ?></span><span><?= (int) $p['stock_quantity'] ?> left <?= stock_badge((int) $p['stock_quantity']) ?></span></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-12 col-xl-7">
    <div class="panel">
      <h2 class="panel-title"><i class="fa-solid fa-cart-shopping"></i> Recent Holiday Sales</h2>
      <?php if (! $recent): ?>
        <?= view('partials/empty', ['icon' => 'fa-snowflake', 'message' => 'No holiday sales have been recorded yet.', 'url' => site_url('sales/create'), 'label' => 'Record Holiday Sale', 'btnIcon' => 'fa-cart-shopping']) ?>
      <?php else: ?>
      <div class="table-responsive">
        <table class="table table-haven align-middle mb-0">
          <thead><tr><th>Product</th><th>Qty</th><th>Total</th><th>Date</th></tr></thead>
          <tbody>
          <?php foreach ($recent as $s): ?>
            <tr><td><?= esc($s['product_name']) ?></td><td><?= (int) $s['quantity'] ?></td>
            <td><span class="price"><?= peso($s['total_price']) ?></span></td>
            <td class="text-nowrap"><?= fmt_dt($s['created_at']) ?></td></tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
