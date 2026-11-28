<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Christmas Products<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
$needRestock = count(array_filter($products, fn($p) => (int) $p['stock_quantity'] <= LOW_STOCK_LIMIT));
$invValue    = array_sum(array_map(fn($p) => (float) $p['price'] * (int) $p['stock_quantity'], $products));
?>
<div class="page-head">
  <div><h1><i class="fa-solid fa-tree"></i> Christmas Products</h1><p class="text-muted mb-0">Trees, lights, ornaments and holiday decorations.</p></div>
  <a href="<?= site_url('products/create') ?>" class="btn btn-green"><i class="fa-solid fa-gift"></i> Add Christmas Product</a>
</div>

<?php if ($products): ?>
  <?= view('partials/stats', ['col' => 'col-12 col-md-4', 'cards' => [
      ['Total Christmas Products', number_format(count($products)), 'fa-tree', 'green'],
      ['Low or Out of Stock', number_format($needRestock), 'fa-bell', 'red'],
      ['Inventory Value', peso($invValue), 'fa-coins', 'gold'],
  ]]) ?>
<?php endif; ?>

<div class="panel">
<?php if (! $products): ?>
  <?= view('partials/empty', ['icon' => 'fa-tree', 'message' => 'No Christmas products are available yet.', 'url' => site_url('products/create'), 'label' => 'Add Christmas Product', 'btnIcon' => 'fa-gift']) ?>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-haven align-middle mb-0">
      <thead><tr><th>Image</th><th>Product</th><th>Price</th><th>Stock</th><th>Status</th><th>Created</th><th class="text-end">Actions</th></tr></thead>
      <tbody>
      <?php foreach ($products as $p): ?>
        <tr>
          <td><?= product_thumb($p['name'], $p['image']) ?></td>
          <td class="fw-semibold"><?= esc($p['name']) ?></td>
          <td><span class="price"><?= peso($p['price']) ?></span></td>
          <td><?= (int) $p['stock_quantity'] ?></td>
          <td><?= stock_badge((int) $p['stock_quantity']) ?></td>
          <td class="text-nowrap"><?= fmt_date($p['created_at']) ?></td>
          <td class="text-end text-nowrap">
            <?= btn_edit(site_url('products/edit/' . $p['id'])) ?>
            <?= btn_delete(site_url('products/delete/' . $p['id']), 'Remove “' . $p['name'] . '” from the Christmas catalog? This cannot be undone.', 'Remove', 'fa-box-archive') ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
</div>
<?= $this->endSection() ?>
