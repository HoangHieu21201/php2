<?php
class CartModel extends Model
{
    private $table = 'carts';

    public function getCartByUserId($userId)
    {
        $sql = "SELECT 
                    c.id as cart_id, 
                    c.quantity, 
                    c.product_variant_id,
                    p.id as product_id,
                    p.name as product_name, 
                    pv.price as variant_price, 
                    pv.sale_price as variant_sale_price,
                    pv.image as variant_image, 
                    p.image as product_image,
                    GROUP_CONCAT(CONCAT(a.name, ': ', av.value) SEPARATOR '|||') as attribute_raw
                FROM $this->table c
                JOIN product_variants pv ON c.product_variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                LEFT JOIN variant_attribute_values vav ON pv.id = vav.product_variant_id
                LEFT JOIN attribute_values av ON vav.attribute_value_id = av.id
                LEFT JOIN attributes a ON av.attribute_id = a.id
                WHERE c.user_id = :user_id
                GROUP BY c.id, pv.id, p.id";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findCartItem($userId, $variantId)
    {
        $sql = "SELECT * FROM $this->table WHERE user_id = :user_id AND product_variant_id = :variant_id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId, ':variant_id' => $variantId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($data)
    {
        $sql = "INSERT INTO $this->table (user_id, product_variant_id, quantity) VALUES (:user_id, :variant_id, :quantity)";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateQuantity($cartId, $quantity)
    {
        $sql = "UPDATE $this->table SET quantity = :quantity WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':quantity' => $quantity, ':id' => $cartId]);
    }

    public function remove($cartId, $userId)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id AND user_id = :user_id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $cartId, ':user_id' => $userId]);
    }

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