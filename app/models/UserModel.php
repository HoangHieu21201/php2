<?php
class UserModel extends Model
{
    private $table = 'users';

    public function all()
    {
        $sql = "SELECT * FROM $this->table ORDER BY id DESC";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO $this->table (name, email, password, phone, address, role, status) 
                VALUES (:name, :email, :password, :phone, :address, :role, :status)";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        if (empty($data['password'])) {
            unset($data['password']);
            $sql = "UPDATE $this->table SET name=:name, email=:email, phone=:phone, address=:address, role=:role, status=:status WHERE id=:id";
        } else {
            $sql = "UPDATE $this->table SET name=:name, email=:email, password=:password, phone=:phone, address=:address, role=:role, status=:status WHERE id=:id";
        }
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}