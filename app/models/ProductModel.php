<?php
class ProductModel extends Model
{
    private $table = 'products';

    // 1. Lấy danh sách (Kèm khoảng giá Min-Max từ biến thể)
    public function all()
    {
        // Thêm sub-query lấy min_price và max_price từ bảng product_variants
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       b.name as brand_name,
                       (SELECT MIN(price) FROM product_variants WHERE product_id = p.id AND deleted_at IS NULL) as min_price,
                       (SELECT MAX(price) FROM product_variants WHERE product_id = p.id AND deleted_at IS NULL) as max_price
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

    // 2. Tìm chi tiết (Cũng cần lấy range giá nếu muốn hiển thị ở trang detail)
    public function find($id)
    {
        $sql = "SELECT p.*,
                       (SELECT MIN(price) FROM product_variants WHERE product_id = p.id AND deleted_at IS NULL) as min_price,
                       (SELECT MAX(price) FROM product_variants WHERE product_id = p.id AND deleted_at IS NULL) as max_price
                FROM $this->table p 
                WHERE p.id = :id AND p.deleted_at IS NULL";

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Thêm mới
    public function create($data)
    {
        $sql = "INSERT INTO $this->table 
                (name, price, image, description, short_description, category_id, brand_id, status) 
                VALUES 
                (:name, :price, :image, :description, :short_description, :category_id, :brand_id, :status)";

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
    }

    // 4. Cập nhật
    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE $this->table SET 
                name = :name, 
                price = :price, 
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

    // 5. Xóa mềm
    public function delete($id)
    {
        $sql = "UPDATE $this->table SET deleted_at = NOW() WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
