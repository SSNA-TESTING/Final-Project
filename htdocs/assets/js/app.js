document.addEventListener('DOMContentLoaded', () => {
  const peso = n => '₱' + Number(n).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
  const hasBS = typeof bootstrap !== 'undefined';

  // Tooltips for icon-only buttons (labels are also exposed via aria-label)
  if (hasBS) document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el, {trigger: 'hover focus'}));

  // Delete confirmation dialog for any <form data-confirm="...">

  // Delete confirmation dialog for any <form data-confirm="...">
  const modalEl = document.getElementById('confirmModal');

  if (modalEl) {
    const text = document.getElementById('confirmText');
    const yesButton = document.getElementById('confirmYes');
    const hasModal = hasBS && typeof bootstrap.Modal !== 'undefined';

    const modal = hasModal ? new bootstrap.Modal(modalEl) : null;
    let pending = null;

    function openConfirmModal() {
      if (modal) {
        modal.show();
      } else {
        modalEl.style.display = 'block';
        modalEl.classList.add('show');
        modalEl.removeAttribute('aria-hidden');
        modalEl.setAttribute('aria-modal', 'true');
        modalEl.setAttribute('role', 'dialog');

        document.body.classList.add('modal-open');

        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'confirmModalBackdrop';
        document.body.appendChild(backdrop);
      }
    }

    function closeConfirmModal() {
      if (modal) {
        modal.hide();
      } else {
        modalEl.classList.remove('show');
        modalEl.style.display = 'none';
        modalEl.setAttribute('aria-hidden', 'true');
        modalEl.removeAttribute('aria-modal');

        document.body.classList.remove('modal-open');

        document.getElementById('confirmModalBackdrop')?.remove();
      }

      pending = null;
    }

    document.querySelectorAll('form[data-confirm]').forEach(form => {
      form.addEventListener('submit', e => {
        e.preventDefault();

        pending = form;
        text.textContent = form.dataset.confirm;

        openConfirmModal();
      });
    });

    yesButton.addEventListener('click', () => {
      if (!pending) return;

      const form = pending;
      pending = null;

      if (modal) {
        modal.hide();
      } else {
        closeConfirmModal();
      }

      HTMLFormElement.prototype.submit.call(form);
    });

    if (!modal) {
      modalEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
        button.addEventListener('click', e => {
          e.preventDefault();
          closeConfirmModal();
        });
      });

      document.getElementById('confirmModalBackdrop')?.addEventListener('click', closeConfirmModal);
    }
  }


  // Image upload preview (product image / staff avatar)
  document.querySelectorAll('input[type=file][data-preview]').forEach(input => {
    input.addEventListener('change', () => {
      const box = document.querySelector(input.dataset.preview), file = input.files[0];
      if (!box || !file || !file.type.startsWith('image/')) return;
      const img = new Image();
      img.className = input.dataset.previewClass || 'thumb';
      img.alt = 'Preview';
      img.src = URL.createObjectURL(file);
      box.replaceChildren(img);
    });
  });

  // Sale form: live total and stock hint (the server re-validates everything)
  const sel = document.getElementById('product_id'), qty = document.getElementById('quantity');
  if (sel && qty) {
    const total = document.getElementById('totalPrice'), hint = document.getElementById('stockHint');
    const update = () => {
      const o = sel.options[sel.selectedIndex], price = parseFloat(o.dataset.price || 0), stock = parseInt(o.dataset.stock || 0, 10);
      const q = parseInt(qty.value, 10) || 0;
      total.textContent = peso(price * Math.max(q, 0));
      hint.textContent = sel.value ? 'Available Holiday Stock: ' + stock : 'Available Holiday Stock: —';
      if (sel.value) qty.max = stock;
    };
    sel.addEventListener('change', update);
    qty.addEventListener('input', update);
    update();
  }

  // Sales history: "View Details" modal
  const saleEl = document.getElementById('saleModal');
  if (saleEl && hasBS) {
    const saleModal = new bootstrap.Modal(saleEl);
    document.querySelectorAll('.js-sale-details').forEach(btn => btn.addEventListener('click', () => {
      const d = btn.dataset;
      saleEl.querySelectorAll('[data-f]').forEach(el => {
        const k = el.dataset.f;
        el.textContent = k === 'id' ? '#' + d.id : (d[k] ?? '');
      });
      saleModal.show();
    }));
  }
});
