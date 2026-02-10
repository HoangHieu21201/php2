<?php
class OrderDetailModel extends Model
{
    private $table = 'order_details';

    public function create($data)
    {
        $sql = "INSERT INTO $this->table (order_id, product_variant_id, quantity, price, total_price) 
                VALUES (:order_id, :product_variant_id, :quantity, :price, :total_price)";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function getByOrderId($orderId)
    {
        $sql = "SELECT od.*, p.name as product_name, pv.image as variant_image 
                FROM $this->table od
                JOIN product_variants pv ON od.product_variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                WHERE od.order_id = :order_id";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}