<?php
class ProductVariantModel extends Model
{
    private $table = 'product_variants';

    public function all()
    {
        $sql = "SELECT pv.*, 
                       p.name as product_name, 
                       c.name as color_name, 
                       s.name as size_name 
                FROM $this->table pv
                LEFT JOIN products p ON pv.product_id = p.id
                LEFT JOIN colors c ON pv.color_id = c.id
                LEFT JOIN sizes s ON pv.size_id = s.id
                WHERE pv.deleted_at IS NULL 
                ORDER BY pv.id DESC";

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Tìm chi tiết 1 biến thể
    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id AND deleted_at IS NULL";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Thêm mới biến thể
    public function create($data)
    {
        $sql = "INSERT INTO $this->table 
                (product_id, color_id, size_id, sku, quantity, price, sale_price, image, status) 
                VALUES 
                (:product_id, :color_id, :size_id, :sku, :quantity, :price, :sale_price, :image, :status)";

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
    }

    // 4. Cập nhật biến thể
    public function update($id, $data)
    {
        $data['id'] = $id; 
        $sql = "UPDATE $this->table SET 
                product_id = :product_id,
                color_id = :color_id,
                size_id = :size_id,
                sku = :sku,
                quantity = :quantity,
                price = :price,
                sale_price = :sale_price,
                image = :image,
                status = :status
                WHERE id = :id";

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
    }

    // 5. Xóa mềm
    public function delete($id)
    {
        $sql = "UPDATE $this->table SET deleted_at = NOW() WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}