<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= $product ? 'Update Product' : 'Add Christmas Product' ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head"><h1><i class="fa-solid fa-gift"></i> <?= $product ? 'Update Christmas Product' : 'Add Christmas Product' ?></h1></div>
<div class="panel form-panel">
  <form method="post" enctype="multipart/form-data" action="<?= site_url($product ? 'products/update/' . $product['id'] : 'products/store') ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label class="form-label" for="name">Product Name</label>
      <input class="form-control" id="name" name="name" maxlength="100" required placeholder="e.g. Pre-Lit Christmas Tree" value="<?= esc(old('name', $product['name'] ?? '')) ?>">
    </div>
    <div class="row g-3 mb-3">
      <div class="col-md-6"><label class="form-label" for="price">Price (₱)</label>
        <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" required placeholder="1499.00" value="<?= esc(old('price', $product['price'] ?? '')) ?>"></div>
      <div class="col-md-6"><label class="form-label" for="stock_quantity">Stock Quantity</label>
        <input type="number" min="0" class="form-control" id="stock_quantity" name="stock_quantity" required placeholder="25" value="<?= esc(old('stock_quantity', $product['stock_quantity'] ?? '')) ?>"></div>
    </div>
    <div class="mb-4">
      <label class="form-label" for="image">Product Image <small class="text-muted">(JPG, PNG or WEBP, max 2 MB)</small></label>
      <input type="file" class="form-control" id="image" name="image" accept="image/png,image/jpeg,image/webp" data-preview="#preview">
      <div class="preview-box mt-2" id="preview"><?= product_thumb($product['name'] ?? 'Christmas product', $product['image'] ?? null) ?></div>
    </div>
    <button class="btn <?= $product ? 'btn-gold' : 'btn-green' ?>"><i class="fa-solid fa-star"></i> <?= $product ? 'Update Product' : 'Save Product' ?></button>
    <a href="<?= site_url('products') ?>" class="btn btn-dark-green">Cancel</a>
  </form>
</div>
<?= $this->endSection() ?>
