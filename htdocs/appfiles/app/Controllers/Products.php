<?php
namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    private array $rules = [
        'name'           => 'required|max_length[100]',
        'price'          => 'required|decimal|greater_than_equal_to[0]',
        'stock_quantity' => 'required|is_natural',
        'image'          => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
    ];

    public function index()
    {
        return view('products/index', ['products' => (new ProductModel())->orderBy('id', 'DESC')->findAll()]);
    }

    public function create() { return view('products/form', ['product' => null]); }

    public function store()
    {
        if (! $this->validate($this->rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        (new ProductModel())->insert($this->data(null));
        return redirect()->to('/products')->with('success', 'Christmas product added successfully!');
    }

    public function edit(int $id)
    {
        $p = (new ProductModel())->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('products/form', ['product' => $p]);
    }

    public function update(int $id)
    {
        $m = new ProductModel();
        $p = $m->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (! $this->validate($this->rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        $m->update($id, $this->data($p));
        return redirect()->to('/products')->with('success', 'Christmas product updated successfully!');
    }

    public function delete(int $id)
    {
        $m = new ProductModel();
        $p = $m->find($id);
        if (! $p) return redirect()->to('/products')->with('error', 'Product not found.');
        if (db_connect()->table('sales')->where('product_id', $id)->countAllResults() > 0) {
            return redirect()->to('/products')->with('error', 'This item has holiday sales on record, so it cannot be removed. Set its stock to 0 instead.');
        }
        $m->delete($id);
        if ($p['image']) @unlink(FCPATH . 'uploads/products/' . $p['image']);
        return redirect()->to('/products')->with('success', 'Christmas product removed.');
    }

    private function data(?array $old): array
    {
        $d = [
            'name'           => trim((string) $this->request->getPost('name')),
            'price'          => $this->request->getPost('price'),
            'stock_quantity' => (int) $this->request->getPost('stock_quantity'),
        ];
        if ($img = save_upload($this->request->getFile('image'), 'products')) {
            $d['image'] = $img;
            if ($old && $old['image']) @unlink(FCPATH . 'uploads/products/' . $old['image']);
        }
        return $d;
    }
}
