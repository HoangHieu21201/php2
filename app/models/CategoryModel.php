<?php
class CategoryModel extends Model {
    private $table = 'categories';
    public function all() {
        $sql = "SELECT * FROM $this->table";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt ->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
//	id	name description status
    public function create($data) {
        $sql = "INSERT INTO $this->table (name, description, status) VALUES (:name, :description, :status)";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':status' => $data['status'],
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table SET name = :name, description = :description, status = :status WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}