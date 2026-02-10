<?php
class UserAddressModel extends Model
{
    private $table = 'user_addresses';

    public function getByUserId($userId)
    {
        $sql = "SELECT * FROM $this->table WHERE user_id = :user_id ORDER BY is_default DESC, id DESC";
        $conn = $this->connect($sql); // Đã sửa: Truyền $sql vào connect
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy địa chỉ mặc định của user
    public function getDefaultAddress($userId)
    {
        $sql = "SELECT * FROM $this->table WHERE user_id = :user_id AND is_default = 1";
        $conn = $this->connect($sql); // Đã sửa
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nếu không có mặc định, lấy cái mới nhất
        if (!$result) {
            $sql = "SELECT * FROM $this->table WHERE user_id = :user_id ORDER BY id DESC LIMIT 1";
            $conn = $this->connect($sql); // Đã sửa (cho chắc chắn khi query mới)
            $stmt = $conn->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $result;
    }

    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $conn = $this->connect($sql); // Đã sửa
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        // Kiểm tra xem user đã có địa chỉ nào chưa
        $check = $this->getByUserId($data['user_id']);
        
        if (empty($check)) {
            $data['is_default'] = 1;
        }

        $sql = "INSERT INTO $this->table (user_id, recipient_name, recipient_phone, address, is_default) 
                VALUES (:user_id, :recipient_name, :recipient_phone, :address, :is_default)";
        $conn = $this->connect($sql); // Đã sửa
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE $this->table 
                SET recipient_name = :recipient_name, 
                    recipient_phone = :recipient_phone, 
                    address = :address 
                WHERE id = :id";
        $conn = $this->connect($sql); // Đã sửa
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function setDefault($userId, $addressId)
    {
        // Transaction thường dùng connect chung, nhưng để an toàn ta truyền sql dummy hoặc sql đầu tiên
        $sql1 = "UPDATE $this->table SET is_default = 0 WHERE user_id = :user_id";
        $conn = $this->connect($sql1); // Đã sửa
        try {
            $conn->beginTransaction();

            $stmt1 = $conn->prepare($sql1);
            $stmt1->execute([':user_id' => $userId]);

            if ($addressId > 0) {
                $sql2 = "UPDATE $this->table SET is_default = 1 WHERE id = :id AND user_id = :user_id";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([':id' => $addressId, ':user_id' => $userId]);
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }

    public function delete($id, $userId)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id AND user_id = :user_id";
        $conn = $this->connect($sql); // Đã sửa
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
}