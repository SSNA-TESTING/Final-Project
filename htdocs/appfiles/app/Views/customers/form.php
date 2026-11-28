<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= $customer ? 'Edit Customer' : 'Add Customer' ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head"><h1><i class="fa-solid fa-user-plus"></i> <?= $customer ? 'Edit Customer' : 'Add Customer' ?></h1></div>
<div class="panel form-panel">

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger" role="alert">
    <i class="fa-solid fa-circle-exclamation"></i>
    <?= esc(session()->getFlashdata('error')) ?>
  </div>
<?php endif; ?>

  <form method="post" action="<?= site_url($customer ? 'customers/update/' . $customer['id'] : 'customers/store') ?>">
    <?= csrf_field() ?>
    <div class="mb-3"><label class="form-label" for="full_name">Full Name</label>
      <input class="form-control" id="full_name" name="full_name" maxlength="100" required value="<?= esc(old('full_name', $customer['full_name'] ?? '')) ?>"></div>
    <div class="mb-3"><label class="form-label" for="email">Email</label>
      <input type="email" class="form-control" id="email" name="email" maxlength="100" required value="<?= esc(old('email', $customer['email'] ?? '')) ?>"></div>
    <div class="mb-4"><label class="form-label" for="phone">Phone</label>
      <input class="form-control" id="phone" name="phone" maxlength="20" value="<?= esc(old('phone', $customer['phone'] ?? '')) ?>"></div>
    <button class="btn <?= $customer ? 'btn-gold' : 'btn-green' ?>"><i class="fa-solid fa-star"></i> <?= $customer ? 'Update Customer' : 'Save Customer' ?></button>
    <a href="<?= site_url('customers') ?>" class="btn btn-dark-green">Cancel</a>
  </form>
</div>
<?= $this->endSection() ?>
