<?php
require_once __DIR__ . '/../Database.php';

class Appointment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new appointment.
     */
    public function create($data) {
        $sql = "INSERT INTO marcacoes (servico, barbeiro, data_marcacao, horario_marcacao, nome_utilizador, telefone_utilizador, email_utilizador, estado) 
                VALUES (:service, :barber, :date, :time, :name, :phone, :email, 'marcada')";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':service' => $data['service'],
            ':barber' => $data['barber'],
            ':date' => $data['date'],
            ':time' => $data['time'],
            ':name' => $data['name'],
            ':phone' => $data['phone'],
            ':email' => $data['email']
        ]);
    }

    /**
     * Check if a slot is taken.
     */
    public function isSlotTaken($barber, $date, $time) {
        $sql = "SELECT COUNT(*) FROM marcacoes WHERE barbeiro = :barber AND data_marcacao = :date AND horario_marcacao = :time AND estado = 'marcada'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':barber' => $barber, ':date' => $date, ':time' => $time]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Get future appointments for a barber.
     */
    public function getFutureByBarber($barber) {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        
        $sql = "SELECT * FROM marcacoes 
                WHERE barbeiro = :barber 
                AND estado NOT IN ('concluída', 'cancelada')
                AND (data_marcacao > :date OR (data_marcacao = :date AND horario_marcacao >= :time))
                ORDER BY data_marcacao ASC, horario_marcacao ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':barber' => $barber, ':date' => $date, ':time' => $time]);
        return $stmt->fetchAll();
    }

    /**
     * Get past pending appointments for a barber.
     */
    public function getPastPendingByBarber($barber) {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        
        $sql = "SELECT * FROM marcacoes 
                WHERE barbeiro = :barber 
                AND estado = 'marcada'
                AND (data_marcacao < :date OR (data_marcacao = :date AND horario_marcacao < :time))
                ORDER BY data_marcacao DESC, horario_marcacao DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':barber' => $barber, ':date' => $date, ':time' => $time]);
        return $stmt->fetchAll();
    }

    /**
     * Update appointment status.
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE marcacoes SET estado = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * Get summary stats for dashboard.
     */
    public function getSummary($period = 'weekly') {
        $condition = "";
        switch ($period) {
            case 'daily':
                $condition = "data_marcacao = CURDATE()";
                break;
            case 'monthly':
                $condition = "MONTH(data_marcacao) = MONTH(CURDATE()) AND YEAR(data_marcacao) = YEAR(CURDATE())";
                break;
            case 'weekly':
            default:
                $condition = "YEARWEEK(data_marcacao, 1) = YEARWEEK(CURDATE(), 1)";
                break;
        }

        $sql = "SELECT 
                    COUNT(*) AS total_bookings,
                    SUM(CASE WHEN estado = 'concluida' THEN 1 ELSE 0 END) AS completed_bookings,
                    SUM(CASE WHEN estado = 'marcada' THEN 1 ELSE 0 END) AS pending_bookings,
                    SUM(CASE WHEN estado = 'cancelada' THEN 1 ELSE 0 END) AS canceled_bookings
                FROM marcacoes
                WHERE $condition";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
?>
