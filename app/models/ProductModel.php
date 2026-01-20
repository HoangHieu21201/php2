<?php
class ProductModel extends Model
{
    private $table = 'products';

    public function all()
    {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM $this->table p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.deleted_at IS NULL
                ORDER BY p.created_at DESC";

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Tìm chi tiết 1 sản phẩm
    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id AND deleted_at IS NULL";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Thêm mới
    public function create($data)
    {
        $sql = "INSERT INTO $this->table 
                (name, price, sale_price, quantity, image, description, short_description, category_id, brand_id, status) 
                VALUES 
                (:name, :price, :sale_price, :quantity, :image, :description, :short_description, :category_id, :brand_id, :status)";

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
    }

    // 4. Cập nhật
    public function update($id, $data)
    {
        $data['id'] = $id; // Thêm ID vào mảng data để bind
        $sql = "UPDATE $this->table SET 
                name = :name, 
                price = :price, 
                sale_price = :sale_price, 
                quantity = :quantity, 
                image = :image, 
                description = :description, 
                short_description = :short_description, 
                category_id = :category_id, 
                brand_id = :brand_id, 
                status = :status 
                WHERE id = :id";

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "UPDATE $this->table SET deleted_at = NOW() WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
