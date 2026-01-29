<?php

class PasswordReset extends Model
{
    private $table = 'password_resets';

    // Tạo bản ghi token mới
    public function create($data)
    {
        // $data bao gồm ['email' => ..., 'token' => ..., 'created_at' => ...]
        $sql = "INSERT INTO $this->table (email, token, created_at) VALUES (:email, :token, :created_at)";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    // Tìm bản ghi dựa trên email và token
    public function findByEmailAndToken($email, $token)
    {
        $sql = "SELECT * FROM $this->table WHERE email = :email AND token = :token";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':token' => $token
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa tất cả token của một email (dùng để clean token cũ trước khi tạo mới hoặc sau khi reset thành công)
    public function deleteByEmail($email)
    {
        $sql = "DELETE FROM $this->table WHERE email = :email";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':email' => $email]);
    }
}