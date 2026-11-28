<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Staff Members<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
$withSales = count(array_filter($staff, fn($u) => (int) $u['sales_count'] > 0));
$recent    = count(array_filter($staff, fn($u) => strtotime($u['created_at']) >= strtotime('-30 days')));
?>
<div class="page-head">
  <div><h1><i class="fa-solid fa-user-tie"></i> Staff Members</h1><p class="text-muted mb-0">Your holiday helpers.</p></div>
  <a href="<?= site_url('staff/create') ?>" class="btn btn-green"><i class="fa-solid fa-user-plus"></i> Add Staff Member</a>
</div>

<?php if ($staff): ?>
  <?= view('partials/stats', ['col' => 'col-12 col-md-4', 'cards' => [
      ['Total Staff Members', number_format(count($staff)), 'fa-user-tie', 'green'],
      ['Staff With Recorded Sales', number_format($withSales), 'fa-cart-shopping', 'red'],
      ['Recently Added (30 Days)', number_format($recent), 'fa-star', 'gold'],
  ]]) ?>
<?php endif; ?>

<div class="panel">
<?php if (! $staff): ?>
  <?= view('partials/empty', ['icon' => 'fa-bell', 'message' => 'No staff members have been added yet.', 'url' => site_url('staff/create'), 'label' => 'Add Staff Member', 'btnIcon' => 'fa-user-plus']) ?>
<?php else: ?>
  <div class="table-responsive"><table class="table table-haven align-middle mb-0">
    <thead><tr><th>Avatar</th><th>Full Name</th><th>Username</th><th>Holiday Sales</th><th>Joined</th><th class="text-end">Actions</th></tr></thead><tbody>
    <?php foreach ($staff as $u): $me = (int) $u['id'] === (int) session('user_id'); ?>
      <tr>
        <td><?= avatar_img($u['avatar'], $u['full_name']) ?></td>
        <td class="fw-semibold"><?= esc($u['full_name']) ?> <?= $me ? '<span class="badge badge-in ms-1">You</span>' : '' ?></td>
        <td><?= esc($u['username']) ?></td>
        <td><?= (int) $u['sales_count'] ?></td>
        <td class="text-nowrap"><?= fmt_date($u['created_at']) ?></td>
        <td class="text-end text-nowrap">
          <?= btn_edit(site_url('staff/edit/' . $u['id'])) ?>
          <?= btn_delete(site_url('staff/delete/' . $u['id']), 'Delete staff member “' . $u['full_name'] . '”? This cannot be undone.') ?>
        </td>
      </tr>
    <?php endforeach; ?></tbody></table></div>
<?php endif; ?>
</div>
<?= $this->endSection() ?>
