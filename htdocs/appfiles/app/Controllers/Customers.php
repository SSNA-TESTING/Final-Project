<?php
namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    private array $rules = [
        'full_name' => 'required|max_length[100]',
        'email'     => 'required|valid_email|max_length[100]',
        'phone'     => 'permit_empty|max_length[20]',
    ];

    public function index()
    {
        $withPurchases = (int) db_connect()->query('SELECT COUNT(DISTINCT customer_id) AS c FROM sales WHERE customer_id IS NOT NULL')->getRow()->c;
        return view('customers/index', [
            'customers'     => (new CustomerModel())->orderBy('id', 'DESC')->findAll(),
            'withPurchases' => $withPurchases,
        ]);
    }

    public function create() { return view('customers/form', ['customer' => null]); }

    public function store()
    {
    if (! $this->validate($this->rules)) {
        return redirect()->back()->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    $email = strtolower(trim((string) $this->request->getPost('email')));

    $duplicate = db_connect()->query(
        'SELECT id FROM customers WHERE LOWER(email) = ? LIMIT 1',
        [$email]
    )->getRow();

    if ($duplicate) {
        return redirect()->back()->withInput()
            ->with('error', 'This email address is already registered.');
    }

    (new CustomerModel())->insert([
        'full_name' => $this->request->getPost('full_name'),
        'email'     => $email,
        'phone'     => $this->request->getPost('phone'),
    ]);

    return redirect()->to('/customers')
        ->with('success', 'Customer registered for the holiday season!');
}
    

    public function edit(int $id)
    {
        $c = (new CustomerModel())->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('customers/form', ['customer' => $c]);
    }

    public function update(int $id)
    {
        $m = new CustomerModel();
        $m->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (! $this->validate($this->rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        $m->update($id, $this->request->getPost(['full_name', 'email', 'phone']));
        return redirect()->to('/customers')->with('success', 'Customer updated successfully!');
    }

    public function delete(int $id)
    {
        if (db_connect()->table('sales')->where('customer_id', $id)->countAllResults() > 0) {
            return redirect()->to('/customers')->with('error', 'This customer has holiday sales on record and cannot be removed.');
        }
        (new CustomerModel())->delete($id);
        return redirect()->to('/customers')->with('success', 'Customer removed.');
    }
}
