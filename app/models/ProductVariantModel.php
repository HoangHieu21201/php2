<?php
class ProductVariantModel extends Model
{
    private $table = 'product_variants';

    public function all()
    {
        $sql = "SELECT pv.*, p.name as product_name,
                GROUP_CONCAT(CONCAT(a.name, ': ', av.value) SEPARATOR ', ') as attributes_string
                FROM $this->table pv
                JOIN products p ON pv.product_id = p.id
                LEFT JOIN variant_attribute_values vav ON pv.id = vav.product_variant_id
                LEFT JOIN attribute_values av ON vav.attribute_value_id = av.id
                LEFT JOIN attributes a ON av.attribute_id = a.id
                WHERE pv.deleted_at IS NULL
                GROUP BY pv.id
                ORDER BY pv.id DESC";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id AND deleted_at IS NULL";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO $this->table 
                (product_id, sku, price, sale_price, quantity, image, description, status) 
                VALUES 
                (:product_id, :sku, :price, :sale_price, :quantity, :image, :description, :status)";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
        return $conn->lastInsertId();
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE $this->table SET 
                product_id = :product_id, 
                sku = :sku, 
                price = :price, 
                sale_price = :sale_price, 
                quantity = :quantity, 
                image = :image, 
                description = :description, 
                status = :status 
                WHERE id = :id";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "UPDATE $this->table SET deleted_at = NOW() WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    public function decreaseStock($id, $quantity)
    {
        $sql = "UPDATE $this->table 
                SET quantity = quantity - :quantity 
                WHERE id = :id AND quantity >= :quantity";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([':quantity' => $quantity, ':id' => $id]);
        
        return $stmt->rowCount() > 0;
    }
    
    public function getByProductId($productId)
    {
        $sql = "SELECT pv.*,
                GROUP_CONCAT(CONCAT(a.name, ': ', av.value) SEPARATOR ', ') as attributes_string
                FROM $this->table pv
                LEFT JOIN variant_attribute_values vav ON pv.id = vav.product_variant_id
                LEFT JOIN attribute_values av ON vav.attribute_value_id = av.id
                LEFT JOIN attributes a ON av.attribute_id = a.id
                WHERE pv.product_id = :product_id AND pv.deleted_at IS NULL
                GROUP BY pv.id";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}