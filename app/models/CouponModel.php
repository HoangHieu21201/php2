<?php
class CouponModel extends Model
{
    private $table = 'coupons';

    // 1. Lấy danh sách (Bỏ qua xóa mềm)
    public function all()
    {
        $sql = "SELECT * FROM $this->table WHERE deleted_at IS NULL ORDER BY id DESC";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Tìm chi tiết
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
        $sql = "INSERT INTO $this->table (code, end_date, max_usage, min_order_value, start_date,times_used,type,value, status) 
        VALUES (:code, :end_date, :max_usage, :min_order_value, :start_date, :times_used, :type, :value, :status)";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
    }

    // 4. Cập nhật
    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE $this->table SET code = :code, end_date = :end_date, max_usage = :max_usage, min_order_value = :min_order_value, start_date = :start_date, times_used = :times_used, type = :type, value = :value, status = :status WHERE id = :id";
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
