<?php
class ContactModel extends Model
{
    private $table = 'contacts';

    public function getAll($limit, $offset, $search = '')
    {
        $sql = "SELECT * FROM $this->table";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE full_name LIKE :search OR email LIKE :search OR subject LIKE :search";
            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT); 
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll($search = '')
    {
        $sql = "SELECT COUNT(*) as total FROM $this->table";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE full_name LIKE :search OR email LIKE :search OR subject LIKE :search";
            $params[':search'] = "%$search%";
        }

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
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
        $sql = "INSERT INTO $this->table (full_name, email, phone, subject, message, created_at) 
        VALUES (:full_name, :email, :phone, :subject, :message, :created_at)";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE $this->table SET full_name = :full_name, email = :email, phone = :phone, subject = :subject, message = :message WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}