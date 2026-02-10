<?php
class OrderModel extends Model
{
    private $table = 'orders';

    public function create($data)
    {
        $sql = "INSERT INTO $this->table (user_id, recipient_name, recipient_phone, recipient_address, subtotal, discount_amount, total_amount, payment_method, payment_status, status, note) 
                VALUES (:user_id, :recipient_name, :recipient_phone, :recipient_address, :subtotal, :discount_amount, :total_amount, :payment_method, :payment_status, :status, :note)";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
        return $conn->lastInsertId();
    }

    public function getOrdersByUserId($userId)
    {
        $sql = "SELECT * FROM $this->table WHERE user_id = :user_id ORDER BY id DESC";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- CÁC HÀM MỚI CHO ADMIN ---

    // Lấy tất cả đơn hàng (Mới nhất lên đầu)
    public function getAll()
    {
        $sql = "SELECT * FROM $this->table ORDER BY id DESC";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status, $payment_status)
    {
        $sql = "UPDATE $this->table SET status = :status, payment_status = :payment_status WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':payment_status' => $payment_status,
            ':id' => $id
        ]);
    }
}