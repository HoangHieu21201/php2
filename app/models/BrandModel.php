<?php
class BrandModel extends Model
{
    private $table = 'brands';

    public function all()
    {
        $sql = "SELECT * FROM $this->table WHERE deleted_at IS NULL ORDER BY id DESC";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id AND deleted_at IS NULL";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByName($name)
    {
        $sql = "SELECT * FROM $this->table WHERE name = :name AND deleted_at IS NULL";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO $this->table (name, slug, image, status) VALUES (:name, :slug, :image, :status)";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE $this->table SET name = :name, slug = :slug, image = :image, status = :status WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "UPDATE $this->table SET deleted_at = NOW() WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}