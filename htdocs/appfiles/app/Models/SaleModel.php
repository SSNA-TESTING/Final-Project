<?php
namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $returnType = 'array';
    protected $allowedFields = ['product_id', 'customer_id', 'sold_by', 'quantity', 'total_price'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $dateFormat = 'datetime';

    private const DETAIL_COLUMNS = 'sales.*, products.name AS product_name, customers.full_name AS customer_name, users.full_name AS staff_name';

    /** Sales joined with product, customer and staff names. */
    public function withDetails(?int $limit = null): array
    {
        $b = $this->select(self::DETAIL_COLUMNS)
            ->join('products', 'products.id = sales.product_id')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->join('users', 'users.id = sales.sold_by')
            ->orderBy('sales.id', 'DESC');
        if ($limit) $b->limit($limit);
        return $b->findAll();
    }

    /** Joins + optional filters: q (product/customer/staff), date_from, date_to, product. Use on a fresh model. */
    private function applyFilters(array $f): self
    {
        $this->join('products', 'products.id = sales.product_id')
             ->join('customers', 'customers.id = sales.customer_id', 'left')
             ->join('users', 'users.id = sales.sold_by');

        if (! empty($f['q'])) {
            $this->groupStart()
                ->like('products.name', $f['q'])
                ->orLike('customers.full_name', $f['q'])
                ->orLike('users.full_name', $f['q'])
                ->groupEnd();
        }
        if (! empty($f['date_from'])) $this->where('sales.created_at >=', $f['date_from'] . ' 00:00:00');
        if (! empty($f['date_to']))   $this->where('sales.created_at <=', $f['date_to'] . ' 23:59:59');
        if (! empty($f['product']))   $this->where('sales.product_id', (int) $f['product']);
        return $this;
    }

    /** Query for the filtered, newest-first list (call findAll($limit, $offset) on the result). */
    public function filtered(array $f): self
    {
        return $this->applyFilters($f)->select(self::DETAIL_COLUMNS)->orderBy('sales.id', 'DESC');
    }

    /** Transactions / items sold / revenue for the same filters. */
    public function summary(array $f): array
    {
        $row = $this->applyFilters($f)
            ->select('COUNT(sales.id) AS n, COALESCE(SUM(sales.quantity), 0) AS items, COALESCE(SUM(sales.total_price), 0) AS revenue', false)
            ->first();
        return $row ?: ['n' => 0, 'items' => 0, 'revenue' => 0];
    }
}
