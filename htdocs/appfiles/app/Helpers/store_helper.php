<?php
const STORE_NAME = 'SSNA Christmas Haven';
const LOW_STOCK_LIMIT = 10;

function peso($n): string { return '₱' . number_format((float) $n, 2); }

/** e.g. November 24, 2026 · 12:28 AM */
function fmt_dt(string $dt): string { return date('F j, Y · g:i A', strtotime($dt)); }
function fmt_date(string $dt): string { return date('F j, Y', strtotime($dt)); }

function stock_badge(int $qty): string
{
    if ($qty <= 0) return '<span class="badge badge-out"><i class="fa-solid fa-ban"></i> Out of Stock</span>';
    if ($qty <= LOW_STOCK_LIMIT) return '<span class="badge badge-low"><i class="fa-solid fa-bell"></i> Low Stock</span>';
    return '<span class="badge badge-in"><i class="fa-solid fa-tree"></i> In Stock</span>';
}

/** Category placeholder icon + tint, guessed from the product name. */
function product_icon(string $name): array
{
    $n = strtolower($name);
    $map = [
        ['wreath', 'fa-holly-berry', 'green'], ['garland', 'fa-holly-berry', 'green'],
        ['lights', 'fa-lightbulb', 'gold'],    ['ornament', 'fa-circle-dot', 'red'],
        ['topper', 'fa-star', 'gold'],         ['star', 'fa-star', 'gold'],
        ['stocking', 'fa-socks', 'red'],       ['snowflake', 'fa-snowflake', 'blue'],
        ['santa', 'fa-sleigh', 'red'],         ['reindeer', 'fa-paw', 'brown'],
        ['candy', 'fa-candy-cane', 'red'],     ['gift', 'fa-gift', 'red'],
        ['bell', 'fa-bell', 'gold'],           ['tree', 'fa-tree', 'green'],
    ];
    foreach ($map as [$kw, $icon, $tone]) {
        if (str_contains($n, $kw)) return [$icon, $tone];
    }
    return ['fa-gift', 'red'];
}

/** Uploaded product image, or a category placeholder. */
function product_thumb(string $name, ?string $file): string
{
    if ($file && is_file(FCPATH . "uploads/products/$file")) {
        return '<img src="' . base_url("uploads/products/$file") . '" class="thumb" alt="' . esc($name, 'attr') . '">';
    }
    [$icon, $tone] = product_icon($name);
    return '<span class="thumb thumb-empty tint-' . $tone . '" role="img" aria-label="' . esc($name, 'attr') . ' (no photo)"><i class="fa-solid ' . $icon . '"></i></span>';
}

/** Circular staff avatar, or a default profile icon. */
function avatar_img(?string $file, string $name = ''): string
{
    if ($file && is_file(FCPATH . "uploads/avatars/$file")) {
        return '<img src="' . base_url("uploads/avatars/$file") . '" class="avatar" alt="' . esc($name, 'attr') . '">';
    }
    return '<span class="avatar avatar-empty" role="img" aria-label="Default profile icon"><i class="fa-solid fa-user"></i></span>';
}

/** Move an uploaded image to public/uploads/<dir>; returns new name or null. */
function save_upload($file, string $dir): ?string
{
    if (! $file || ! $file->isValid() || $file->hasMoved()) return null;
    $name = $file->getRandomName();
    $file->move(FCPATH . "uploads/$dir", $name);
    return $name;
}

/** Gold Edit button (icon + label on md+, icon with tooltip on small screens). */
function btn_edit(string $url, string $label = 'Edit'): string
{
    $l = esc($label, 'attr');
    return '<a href="' . esc($url, 'attr') . '" class="btn btn-gold btn-sm" data-bs-toggle="tooltip" title="' . $l . '" aria-label="' . $l . '">'
         . '<i class="fa-solid fa-pen"></i><span class="d-none d-md-inline"> ' . esc($label) . '</span></a>';
}

/** Red Delete button inside a POST form; the JS confirmation dialog reads data-confirm. */
function btn_delete(string $url, string $confirm, string $label = 'Delete', string $icon = 'fa-trash'): string
{
    $l = esc($label, 'attr');
    return '<form method="post" action="' . esc($url, 'attr') . '" class="d-inline" data-confirm="' . esc($confirm, 'attr') . '">' . csrf_field()
         . '<button class="btn btn-red btn-sm" data-bs-toggle="tooltip" title="' . $l . '" aria-label="' . $l . '">'
         . '<i class="fa-solid ' . $icon . '"></i><span class="d-none d-md-inline"> ' . esc($label) . '</span></button></form>';
}
