<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Holiday Sales History<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
$qs = fn(int $n) => site_url('sales/history') . '?' . http_build_query(array_filter($filters, fn($v) => $v !== '') + ['page' => $n]);
?>
<div class="page-head">
  <div><h1><i class="fa-solid fa-snowflake"></i> Holiday Sales History</h1><p class="text-muted mb-0">Every festive sale, newest first.</p></div>
  <a href="<?= site_url('sales/create') ?>" class="btn btn-green"><i class="fa-solid fa-cart-shopping"></i> Record Holiday Sale</a>
</div>

<?= view('partials/stats', ['col' => 'col-12 col-md-4', 'cards' => [
    [$filtered ? 'Matching Transactions' : 'Total Transactions', number_format((int) $summary['n']), 'fa-receipt', 'green'],
    ['Total Items Sold', number_format((int) $summary['items']), 'fa-gift', 'red'],
    ['Total Revenue', peso($summary['revenue']), 'fa-coins', 'gold'],
]]) ?>

<div class="panel mb-4">
  <form method="get" action="<?= site_url('sales/history') ?>" class="row g-3 align-items-end">
    <div class="col-12 col-lg-4">
      <label class="form-label" for="q">Search</label>
      <input class="form-control" id="q" name="q" placeholder="Product, customer or staff member" value="<?= esc($filters['q'], 'attr') ?>">
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
      <label class="form-label" for="product">Product</label>
      <select class="form-select" id="product" name="product">
        <option value="">All products</option>
        <?php foreach ($products as $p): ?>
          <option value="<?= $p['id'] ?>" <?= (int) $filters['product'] === (int) $p['id'] ? 'selected' : '' ?>><?= esc($p['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-6 col-lg-2"><label class="form-label" for="date_from">From</label>
      <input type="date" class="form-control" id="date_from" name="date_from" value="<?= esc($filters['date_from'], 'attr') ?>"></div>
    <div class="col-6 col-lg-2"><label class="form-label" for="date_to">To</label>
      <input type="date" class="form-control" id="date_to" name="date_to" value="<?= esc($filters['date_to'], 'attr') ?>"></div>
    <div class="col-12 d-flex flex-wrap gap-2">
      <button class="btn btn-dark-green"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
      <button class="btn btn-gold"><i class="fa-solid fa-filter"></i> Filter</button>
      <?php if ($filtered): ?><a href="<?= site_url('sales/history') ?>" class="btn btn-dark-green"><i class="fa-solid fa-rotate-left"></i> Clear</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="panel">
<?php if (! $sales): ?>
  <?php if ($filtered): ?>
    <?= view('partials/empty', ['icon' => 'fa-magnifying-glass', 'message' => 'No holiday sales match your search or filters.', 'url' => site_url('sales/history'), 'label' => 'Clear Filters', 'btnIcon' => 'fa-rotate-left']) ?>
  <?php else: ?>
    <?= view('partials/empty', ['icon' => 'fa-snowflake', 'message' => 'No holiday sales have been recorded yet.', 'url' => site_url('sales/create'), 'label' => 'Record Holiday Sale', 'btnIcon' => 'fa-cart-shopping']) ?>
  <?php endif; ?>
<?php else: ?>
  <div class="table-responsive"><table class="table table-haven align-middle mb-0">
    <thead><tr><th>Sale ID</th><th>Product</th><th>Customer</th><th>Staff Member</th><th>Qty</th><th>Total Price</th><th>Date &amp; Time</th><th class="text-end">Details</th></tr></thead><tbody>
    <?php foreach ($sales as $s):
      $customer = $s['customer_name'] ?: 'Walk-in Customer'; ?>
      <tr>
        <td><span class="badge badge-in">#<?= $s['id'] ?></span></td>
        <td class="fw-semibold"><?= esc($s['product_name']) ?></td>
        <td><?= $s['customer_name'] ? esc($s['customer_name']) : '<span class="text-muted">Walk-in Customer</span>' ?></td>
        <td><?= esc($s['staff_name']) ?></td>
        <td><?= (int) $s['quantity'] ?></td>
        <td><span class="price"><?= peso($s['total_price']) ?></span></td>
        <td class="text-nowrap"><?= fmt_dt($s['created_at']) ?></td>
        <td class="text-end">
          <button type="button" class="btn btn-green btn-sm js-sale-details" data-bs-toggle="tooltip" title="View Details" aria-label="View details for sale #<?= $s['id'] ?>"
            data-id="<?= $s['id'] ?>" data-product="<?= esc($s['product_name'], 'attr') ?>" data-customer="<?= esc($customer, 'attr') ?>"
            data-staff="<?= esc($s['staff_name'], 'attr') ?>" data-qty="<?= (int) $s['quantity'] ?>"
            data-unit="<?= esc(peso($s['quantity'] > 0 ? $s['total_price'] / $s['quantity'] : 0), 'attr') ?>"
            data-total="<?= esc(peso($s['total_price']), 'attr') ?>" data-date="<?= esc(fmt_dt($s['created_at']), 'attr') ?>">
            <i class="fa-solid fa-eye"></i><span class="d-none d-md-inline"> View Details</span></button>
        </td>
      </tr>
    <?php endforeach; ?></tbody></table></div>

  <?php if ($pages > 1): ?>
  <nav class="mt-3" aria-label="Sales pages">
    <ul class="pagination pagination-haven justify-content-center flex-wrap mb-0">
      <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= $qs(max(1, $page - 1)) ?>" aria-label="Previous page"><i class="fa-solid fa-chevron-left"></i></a></li>
      <?php for ($i = max(1, $page - 2); $i <= min($pages, $page + 2); $i++): ?>
        <li class="page-item <?= $i === $page ? 'active' : '' ?>"><a class="page-link" href="<?= $qs($i) ?>" <?= $i === $page ? 'aria-current="page"' : '' ?>><?= $i ?></a></li>
      <?php endfor; ?>
      <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>"><a class="page-link" href="<?= $qs(min($pages, $page + 1)) ?>" aria-label="Next page"><i class="fa-solid fa-chevron-right"></i></a></li>
    </ul>
    <p class="text-center text-muted small mt-2 mb-0">Page <?= $page ?> of <?= $pages ?></p>
  </nav>
  <?php endif; ?>
<?php endif; ?>
</div>

<?= $this->endSection() ?>
