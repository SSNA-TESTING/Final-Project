<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Customers<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php $recent = count(array_filter($customers, fn($c) => strtotime($c['created_at']) >= strtotime('-30 days'))); ?>
<div class="page-head">
  <div><h1><i class="fa-solid fa-users"></i> Customers</h1><p class="text-muted mb-0">Everyone on your holiday guest list.</p></div>
  <a href="<?= site_url('customers/create') ?>" class="btn btn-green"><i class="fa-solid fa-user-plus"></i> Add Customer</a>
</div>

<?php if ($customers): ?>
  <?= view('partials/stats', ['col' => 'col-12 col-md-4', 'cards' => [
      ['Registered Customers', number_format(count($customers)), 'fa-users', 'green'],
      ['New in the Last 30 Days', number_format($recent), 'fa-star', 'gold'],
      ['With Holiday Purchases', number_format($withPurchases), 'fa-bag-shopping', 'red'],
  ]]) ?>
<?php endif; ?>

<div class="panel">
<?php if (! $customers): ?>
  <?= view('partials/empty', ['icon' => 'fa-gift', 'message' => 'No holiday customers have been registered yet.', 'url' => site_url('customers/create'), 'label' => 'Add Customer', 'btnIcon' => 'fa-user-plus']) ?>
<?php else: ?>
  <div class="table-responsive"><table class="table table-haven align-middle mb-0">
    <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Registered</th><th class="text-end">Actions</th></tr></thead><tbody>
    <?php foreach ($customers as $c): ?>
      <tr><td class="fw-semibold"><i class="fa-solid fa-gift text-red" aria-hidden="true"></i> <?= esc($c['full_name']) ?></td><td><?= esc($c['email']) ?></td><td><?= esc($c['phone']) ?></td>
      <td class="text-nowrap"><?= fmt_date($c['created_at']) ?></td>
      <td class="text-end text-nowrap">
        <?= btn_edit(site_url('customers/edit/' . $c['id'])) ?>
        <?= btn_delete(site_url('customers/delete/' . $c['id']), 'Delete customer “' . $c['full_name'] . '”? This cannot be undone.') ?>
      </td></tr>
    <?php endforeach; ?></tbody></table></div>
<?php endif; ?>
</div>
<?= $this->endSection() ?>
