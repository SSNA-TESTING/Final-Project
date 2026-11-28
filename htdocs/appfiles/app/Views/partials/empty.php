<?php /** @var string $icon, $message ; optional $url, $label, $btnIcon, $hint */ ?>
<div class="empty-state">
  <div class="empty-icon" aria-hidden="true"><i class="fa-solid <?= $icon ?>"></i></div>
  <p class="empty-msg"><?= esc($message) ?></p>
  <?php if (! empty($hint)): ?><p class="empty-hint"><?= esc($hint) ?></p><?php endif; ?>
  <?php if (! empty($url)): ?>
    <a href="<?= esc($url, 'attr') ?>" class="btn btn-green"><i class="fa-solid <?= $btnIcon ?? 'fa-plus' ?>"></i> <?= esc($label) ?></a>
  <?php endif; ?>
</div>
