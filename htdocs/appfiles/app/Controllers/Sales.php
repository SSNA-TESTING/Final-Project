<?php
namespace App\Controllers;

use App\Models\{ProductModel, CustomerModel, SaleModel};

class Sales extends BaseController
{
    public function create()
    {
        return view('sales/create', [
            'products'  => (new ProductModel())->orderBy('name')->findAll(),
            'customers' => (new CustomerModel())->orderBy('full_name')->findAll(),
        ]);
    }

    public function store()
    {
        $ok = $this->validate([
            'product_id'  => 'required|is_natural_no_zero',
            'customer_id' => 'permit_empty|is_natural_no_zero',
            'quantity'    => 'required|is_natural_no_zero',
        ], [
            'product_id' => ['required' => 'Please select a Christmas product.', 'is_natural_no_zero' => 'Please select a Christmas product.'],
            'quantity'   => ['required' => 'Please enter a valid quantity.', 'is_natural_no_zero' => 'Please enter a valid quantity.'],
        ]);
        if (! $ok) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        $productId  = (int) $this->request->getPost('product_id');
        $customerId = $this->request->getPost('customer_id') ?: null;
        $qty        = (int) $this->request->getPost('quantity');

        $db = db_connect();
        $db->transBegin();
        $p = $db->query('SELECT * FROM products WHERE id = ? FOR UPDATE', [$productId])->getRowArray();

        if (! $p) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'That Christmas product could not be found.');
        }
        if ($customerId && ! $db->table('customers')->where('id', $customerId)->countAllResults()) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'That customer could not be found.');
        }
        if ($qty > (int) $p['stock_quantity']) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Not enough stock available for this holiday item.');
        }

        (new SaleModel())->insert([
            'product_id'  => $productId,
            'customer_id' => $customerId,
            'sold_by'     => session()->get('user_id'),
            'quantity'    => $qty,
            'total_price' => round($qty * (float) $p['price'], 2),
        ]);
        $db->table('products')->set('stock_quantity', 'stock_quantity - ' . $qty, false)->where('id', $productId)->update();

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Something went wrong. The sale was not recorded.');
        }
        $db->transCommit();
        return redirect()->to('/sales/history')->with('success', 'Christmas sale recorded successfully!');
    }

    public function history()
    {
        $f = [
            'q'         => trim((string) $this->request->getGet('q')),
            'date_from' => $this->cleanDate($this->request->getGet('date_from')),
            'date_to'   => $this->cleanDate($this->request->getGet('date_to')),
            'product'   => (int) $this->request->getGet('product') ?: '',
        ];
        $perPage = 15;
        $summary = (new SaleModel())->summary($f);
        $pages   = max(1, (int) ceil(((int) $summary['n']) / $perPage));
        $page    = min(max(1, (int) $this->request->getGet('page')), $pages);

        return view('sales/history', [
            'sales'     => (new SaleModel())->filtered($f)->findAll($perPage, ($page - 1) * $perPage),
            'summary'   => $summary,
            'products'  => (new ProductModel())->orderBy('name')->findAll(),
            'filters'   => $f,
            'filtered'  => (bool) array_filter($f),
            'page'      => $page,
            'pages'     => $pages,
        ]);
    }

    private function cleanDate($v): string
    {
        return (is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) ? $v : '';
    }
}
