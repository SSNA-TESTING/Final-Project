<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Record Holiday Sale<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head"><h1><i class="fa-solid fa-cart-shopping"></i> Record Holiday Sale</h1></div>
<div class="row g-4">
  <div class="col-12 col-lg-7">
    <div class="panel">
      <h2 class="panel-title"><i class="fa-solid fa-gift"></i> Christmas Order Details</h2>
      <?php if (! $products): ?>
        <?= view('partials/empty', ['icon' => 'fa-tree', 'message' => 'No Christmas products are available yet.', 'url' => site_url('products/create'), 'label' => 'Add Christmas Product', 'btnIcon' => 'fa-gift']) ?>
      <?php else: ?>
      <form method="post" action="<?= site_url('sales/store') ?>" id="saleForm">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label" for="product_id">Select a Christmas Product</label>
          <select class="form-select" id="product_id" name="product_id" required>
            <option value="" data-price="0" data-stock="0">— Choose a holiday item —</option>
            <?php foreach ($products as $p): ?>
              <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>" data-stock="<?= (int) $p['stock_quantity'] ?>"
                <?= (int) $p['stock_quantity'] <= 0 ? 'disabled' : '' ?> <?= old('product_id') == $p['id'] ? 'selected' : '' ?>>
                <?= esc($p['name']) ?> — <?= peso($p['price']) ?> (<?= (int) $p['stock_quantity'] ?> in stock)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label" for="customer_id">Customer <small class="text-muted">(optional)</small></label>
          <select class="form-select" id="customer_id" name="customer_id">
            <option value="">Walk-in customer</option>
            <?php foreach ($customers as $c): ?>
              <option value="<?= $c['id'] ?>" <?= old('customer_id') == $c['id'] ? 'selected' : '' ?>><?= esc($c['full_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label" for="quantity">Quantity</label>
          <input type="number" min="1" class="form-control" id="quantity" name="quantity" required value="<?= esc(old('quantity', 1)) ?>">
          <div class="form-text" id="stockHint">Available Holiday Stock: —</div>
        </div>
        <div class="total-box mb-4"><span>Total Price</span><strong id="totalPrice">₱0.00</strong></div>
        <button class="btn btn-green"><i class="fa-solid fa-star"></i> Record Holiday Sale</button>
      </form>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-12 col-lg-5">
    <div class="panel">
      <h2 class="panel-title"><i class="fa-solid fa-snowflake"></i> Available Holiday Stock</h2>
      <ul class="list-clean">
        <?php foreach ($products as $p): ?>
          <li><span><?= esc($p['name']) ?></span><span><?= (int) $p['stock_quantity'] ?> <?= stock_badge((int) $p['stock_quantity']) ?></span></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
