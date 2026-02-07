<?php
class ProductModel extends Model
{
    private $table = 'products';

    public function getAllWithPriceRange()
    {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name,
                (SELECT MIN(sale_price) FROM product_variants WHERE product_id = p.id AND deleted_at IS NULL) as min_price,
                (SELECT MAX(sale_price) FROM product_variants WHERE product_id = p.id AND deleted_at IS NULL) as max_price
                FROM $this->table p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.deleted_at IS NULL AND p.status = 1
                ORDER BY p.id DESC";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function all()
    {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM $this->table p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.deleted_at IS NULL 
                ORDER BY p.id DESC";
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
                (name, image, short_description, category_id, brand_id, status) 
                VALUES 
                (:name, :image, :short_description, :category_id, :brand_id, :status)";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
        return $conn->lastInsertId();
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE $this->table SET 
                name = :name, 
                image = :image, 
                short_description = :short_description, 
                category_id = :category_id, 
                brand_id = :brand_id, 
                status = :status 
                WHERE id = :id";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE $this->table SET status = :status WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function delete($id)
    {
        $sql = "UPDATE $this->table SET deleted_at = NOW() WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}