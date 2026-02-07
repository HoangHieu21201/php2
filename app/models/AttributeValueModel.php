<?php
class AttributeValueModel extends Model
{
    private $table = 'attribute_values';

    public function all()
    {
        $sql = "SELECT av.*, a.name as attribute_name 
                FROM $this->table av
                JOIN attributes a ON av.attribute_id = a.id
                ORDER BY av.attribute_id ASC, av.id ASC";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByAttributeId($attributeId)
    {
        $sql = "SELECT * FROM $this->table WHERE attribute_id = :attribute_id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':attribute_id' => $attributeId]);
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

    public function create($data)
    {
        $sql = "INSERT INTO $this->table (attribute_id, value) VALUES (:attribute_id, :value)";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE $this->table SET value = :value WHERE id = :id";
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