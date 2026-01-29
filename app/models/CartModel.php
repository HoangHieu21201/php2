<?php
class CartModel extends Model
{
    private $table = 'carts';

    // 1. Lấy danh sách giỏ hàng của user
    public function getCartByUserId($userId)
    {
        $sql = "SELECT 
                    c.id as cart_id, 
                    c.quantity, 
                    c.product_variant_id,
                    p.id as product_id,
                    p.name as product_name, 
                    p.price as product_price,
                    pv.price as variant_price, 
                    pv.sale_price as variant_sale_price,
                    pv.image as variant_image, 
                    p.image as product_image,
                    co.name as color_name, 
                    s.name as size_name
                FROM $this->table c
                JOIN product_variants pv ON c.product_variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                LEFT JOIN colors co ON pv.color_id = co.id
                LEFT JOIN sizes s ON pv.size_id = s.id
                WHERE c.user_id = :user_id";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Tìm sản phẩm trong giỏ (để kiểm tra trùng)
    public function findCartItem($userId, $variantId)
    {
        $sql = "SELECT * FROM $this->table WHERE user_id = :user_id AND product_variant_id = :variant_id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId, ':variant_id' => $variantId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Thêm mới vào giỏ
    public function add($data)
    {
        $sql = "INSERT INTO $this->table (user_id, product_variant_id, quantity) VALUES (:user_id, :variant_id, :quantity)";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    // 4. Cập nhật số lượng
    public function updateQuantity($cartId, $quantity)
    {
        $sql = "UPDATE $this->table SET quantity = :quantity WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':quantity' => $quantity, ':id' => $cartId]);
    }

    // 5. Xóa khỏi giỏ
    public function remove($cartId, $userId)
    {
        // Thêm userId vào điều kiện xóa để bảo mật (tránh xóa giỏ người khác)
        $sql = "DELETE FROM $this->table WHERE id = :id AND user_id = :user_id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $cartId, ':user_id' => $userId]);
    }

    // 6. Đếm số lượng sản phẩm trong giỏ (hiển thị lên header)
    public function countCart($userId)
    {
        $sql = "SELECT COUNT(*) as total FROM $this->table WHERE user_id = :user_id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}