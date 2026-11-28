<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= $member ? 'Edit Staff Member' : 'Add Staff Member' ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head"><h1><i class="fa-solid fa-user-plus"></i> <?= $member ? 'Edit Staff Member' : 'Add Staff Member' ?></h1></div>
<div class="panel form-panel">
  <form method="post" enctype="multipart/form-data" action="<?= site_url($member ? 'staff/update/' . $member['id'] : 'staff/store') ?>">
    <?= csrf_field() ?>
    <div class="row g-3 mb-3">
      <div class="col-md-6"><label class="form-label" for="full_name">Full Name</label>
        <input class="form-control" id="full_name" name="full_name" maxlength="100" required value="<?= esc(old('full_name', $member['full_name'] ?? '')) ?>"></div>
      <div class="col-md-6"><label class="form-label" for="username">Username</label>
        <input class="form-control" id="username" name="username" maxlength="50" required value="<?= esc(old('username', $member['username'] ?? '')) ?>"></div>
    </div>
    <div class="mb-3"><label class="form-label" for="password">Password <?= $member ? '<small class="text-muted">(leave blank to keep current)</small>' : '' ?></label>
      <input type="password" class="form-control" id="password" name="password" minlength="6" <?= $member ? '' : 'required' ?> autocomplete="new-password"></div>
    <div class="mb-4"><label class="form-label" for="avatar">Avatar <small class="text-muted">(optional)</small></label>
      <input type="file" class="form-control" id="avatar" name="avatar" accept="image/png,image/jpeg,image/webp" data-preview="#preview" data-preview-class="avatar avatar-lg">
      <div class="preview-box mt-2" id="preview"><?= avatar_img($member['avatar'] ?? null, $member['full_name'] ?? '') ?></div></div>
    <button class="btn <?= $member ? 'btn-gold' : 'btn-green' ?>"><i class="fa-solid fa-star"></i> <?= $member ? 'Update Staff Member' : 'Save Staff Member' ?></button>
    <a href="<?= site_url('staff') ?>" class="btn btn-dark-green">Cancel</a>
  </form>
</div>
<?= $this->endSection() ?>
