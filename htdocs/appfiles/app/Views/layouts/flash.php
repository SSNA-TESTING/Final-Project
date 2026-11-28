<?php if ($m = session()->getFlashdata('success')): ?>
  <div class="alert alert-haven-success alert-dismissible fade show"><i class="fa-solid fa-gift"></i> <?= esc($m) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($m = session()->getFlashdata('error')): ?>
  <div class="alert alert-haven-error alert-dismissible fade show"><i class="fa-solid fa-triangle-exclamation"></i> <?= esc($m) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($errs = session()->getFlashdata('errors')): ?>
  <div class="alert alert-haven-error alert-dismissible fade show"><i class="fa-solid fa-triangle-exclamation"></i>
    <ul class="mb-0 ps-3"><?php foreach ((array) $errs as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
    <button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
