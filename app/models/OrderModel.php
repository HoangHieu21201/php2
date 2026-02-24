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

    public function getRevenueSummary()
    {
        $stats = [];
        $conn = $this->connect("");

        $sqlTotal = "SELECT COUNT(*) as total_orders, SUM(total_amount) as total_revenue 
                     FROM $this->table 
                     WHERE status = 'completed' AND payment_status = 'paid'";
        $stmtTotal = $conn->prepare($sqlTotal);
        $stmtTotal->execute();
        $resTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $stats['total_revenue'] = $resTotal['total_revenue'] ?? 0;
        $stats['total_orders'] = $resTotal['total_orders'] ?? 0;

        $sqlToday = "SELECT SUM(total_amount) FROM $this->table 
                     WHERE status = 'completed' AND payment_status = 'paid' AND DATE(created_at) = CURDATE()";
        $stmtToday = $conn->prepare($sqlToday);
        $stmtToday->execute();
        $stats['today_revenue'] = $stmtToday->fetchColumn() ?? 0;

        $sqlMonth = "SELECT SUM(total_amount) FROM $this->table 
                     WHERE status = 'completed' AND payment_status = 'paid' 
                     AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())";
        $stmtMonth = $conn->prepare($sqlMonth);
        $stmtMonth->execute();
        $stats['month_revenue'] = $stmtMonth->fetchColumn() ?? 0;

        $sqlPending = "SELECT COUNT(*) FROM $this->table WHERE status = 'pending'";
        $stmtPending = $conn->prepare($sqlPending);
        $stmtPending->execute();
        $stats['pending_orders'] = $stmtPending->fetchColumn() ?? 0;

        return $stats;
    }

    public function getDailyRevenue($days = 7)
    {
        $sql = "SELECT 
                    DATE(created_at) as date, 
                    SUM(total_amount) as revenue,
                    COUNT(*) as orders
                FROM $this->table 
                WHERE status = 'completed' AND payment_status = 'paid'
                  AND created_at >= DATE(NOW()) - INTERVAL :days DAY
                GROUP BY DATE(created_at)
                ORDER BY date ASC";

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyRevenue($limit = 12)
    {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as date, 
                    SUM(total_amount) as revenue,
                    COUNT(*) as orders
                FROM $this->table 
                WHERE status = 'completed' AND payment_status = 'paid'
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY date ASC
                LIMIT :limit";

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getYearlyRevenue($limit = 5)
    {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y') as date, 
                    SUM(total_amount) as revenue,
                    COUNT(*) as orders
                FROM $this->table 
                WHERE status = 'completed' AND payment_status = 'paid'
                GROUP BY DATE_FORMAT(created_at, '%Y')
                ORDER BY date ASC
                LIMIT :limit";

        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}