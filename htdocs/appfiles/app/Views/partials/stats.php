<?php /** @var array $cards [label, value, icon, tone] */ $col = $col ?? 'col-12 col-sm-6 col-xl-4'; ?>
<div class="row g-3 mb-4">
  <?php foreach ($cards as [$label, $val, $icon, $tone]): ?>
  <div class="<?= $col ?>">
    <div class="stat-card tone-<?= $tone ?>">
      <div class="stat-icon" aria-hidden="true"><i class="fa-solid <?= $icon ?>"></i></div>
      <div><div class="stat-label"><?= esc($label) ?></div><div class="stat-value"><?= esc($val) ?></div></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
