<?php
namespace App\Controllers;

use App\Models\{ProductModel, CustomerModel, UserModel, SaleModel};

class Dashboard extends BaseController
{
    public function index()
    {
        $products = new ProductModel();
        $sales = new SaleModel();
        return view('dashboard', [
            'totalProducts'  => $products->countAllResults(),
            'totalStock'     => (int) ($products->selectSum('stock_quantity')->first()['stock_quantity'] ?? 0),
            'customers'      => (new CustomerModel())->countAllResults(),
            'staff'          => (new UserModel())->countAllResults(),
            'totalSales'     => $sales->countAllResults(),
            'revenue'        => (float) ($sales->selectSum('total_price')->first()['total_price'] ?? 0),
            'lowStock'       => (new ProductModel())->where('stock_quantity <=', LOW_STOCK_LIMIT)->orderBy('stock_quantity')->findAll(8),
            'recent'         => (new SaleModel())->withDetails(5),
        ]);
    }
}
