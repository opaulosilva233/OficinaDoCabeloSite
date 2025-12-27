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
        $sql = "INSERT INTO marcacoes (servico, barbeiro, data_marcacao, horario_marcacao, nome_utilizador, telefone_utilizador, email_utilizador, observacoes, estado) 
                VALUES (:service, :barber, :date, :time, :name, :phone, :email, :observations, 'marcada')";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':service' => $data['service'],
            ':barber' => $data['barber'],
            ':date' => $data['date'],
            ':time' => $data['time'],
            ':name' => $data['name'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':observations' => $data['observations'] ?? ''
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
     * Get booked slots for a specific barber and date.
     */
    public function getBookedSlots($barber, $date) {
        $sql = "SELECT TIME_FORMAT(horario_marcacao, '%H:%i') AS horario_marcacao 
                FROM marcacoes 
                WHERE TRIM(barbeiro) = :barber AND data_marcacao = :date AND estado = 'marcada'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':barber' => $barber, ':date' => $date]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get fully booked days for a specific month/year.
     */
    public function getFullyBookedDays($barber, $month, $year) {
        // Assuming 21 slots per day (09:00 to 19:00)
        // Adjust this number if your opening hours change
        $totalSlotsPerDay = 21; 

        $sql = "SELECT data_marcacao, COUNT(*) as booked_count 
                FROM marcacoes 
                WHERE barbeiro = :barber 
                AND MONTH(data_marcacao) = :month 
                AND YEAR(data_marcacao) = :year 
                AND estado = 'marcada'
                GROUP BY data_marcacao
                HAVING booked_count >= :totalSlots";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':barber' => $barber,
            ':month' => $month,
            ':year' => $year,
            ':totalSlots' => $totalSlotsPerDay
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
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
    /**
     * Get next upcoming appointments (limit by default 5)
     */
    public function getNextAppointments($limit = 5) {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        
        $sql = "SELECT id, barbeiro, servico, data_marcacao, horario_marcacao, cliente_nome, estado 
                FROM marcacoes 
                WHERE estado IN ('marcada', 'pendente')
                AND (data_marcacao > :date OR (data_marcacao = :date AND horario_marcacao >= :time))
                ORDER BY data_marcacao ASC, horario_marcacao ASC
                LIMIT :limit";
                
        // Note: 'cliente_nome' in DB schema might be 'nome_utilizador'. 
        // Based on create() method: 'nome_utilizador' is the column. 
        // Let's fix the SQL to select 'nome_utilizador' as 'cliente_nome' for easier usage or just use 'nome_utilizador'.
        // Checking create() method again: 
        // INSERT INTO marcacoes (..., nome_utilizador, ...) 
        
        $sql = "SELECT id, barbeiro, servico, data_marcacao, horario_marcacao, nome_utilizador as cliente, estado 
                FROM marcacoes 
                WHERE estado NOT IN ('concluida', 'cancelada')
                AND (data_marcacao > :date OR (data_marcacao = :date AND horario_marcacao >= :time))
                ORDER BY data_marcacao ASC, horario_marcacao ASC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':date', $date);
        $stmt->bindValue(':time', $time);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    /**
     * Get chart statistics based on period.
     */
    public function getChartStats($period) {
        $condition = "";
        $groupBy = "";
        $select = "";
        
        switch ($period) {
            case 'daily':
                // Group by 4-hour blocks
                // 00-04, 04-08, 08-12, 12-16, 16-20, 20-24
                $sql = "SELECT 
                            CASE 
                                WHEN HOUR(horario_marcacao) < 4 THEN '00h-04h'
                                WHEN HOUR(horario_marcacao) < 8 THEN '04h-08h'
                                WHEN HOUR(horario_marcacao) < 12 THEN '08h-12h'
                                WHEN HOUR(horario_marcacao) < 16 THEN '12h-16h'
                                WHEN HOUR(horario_marcacao) < 20 THEN '16h-20h'
                                ELSE '20h-24h'
                            END as time_slot,
                            COUNT(*) as booking_count
                        FROM marcacoes
                        WHERE data_marcacao = CURDATE()
                        GROUP BY time_slot";
                break;
                
            case 'monthly':
                $sql = "SELECT DAY(data_marcacao) as day_of_month, COUNT(*) as booking_count
                        FROM marcacoes
                        WHERE MONTH(data_marcacao) = MONTH(CURDATE()) 
                        AND YEAR(data_marcacao) = YEAR(CURDATE())
                        GROUP BY day_of_month";
                break;
                
            case 'weekly':
            default:
                // Monday to Saturday (or Sunday)
                // ELT(WEEKDAY(data_marcacao) + 1, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo')
                $sql = "SELECT 
                            ELT(WEEKDAY(data_marcacao) + 1, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo') as day_of_week,
                            COUNT(*) as booking_count
                        FROM marcacoes
                        WHERE YEARWEEK(data_marcacao, 1) = YEARWEEK(CURDATE(), 1)
                        GROUP BY day_of_week";
                break;
        }
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Get all appointments with pagination and filters.
     */
    /**
     * Get all appointments with pagination and filters.
     */
    public function getAll($page = 1, $limit = 10, $search = '', $filters = []) {
        $offset = ($page - 1) * $limit;
        $params = [];
        $whereClause = "WHERE 1=1";

        if (!empty($search)) {
            $whereClause .= " AND (nome_utilizador LIKE :search OR telefone_utilizador LIKE :search_phone)";
            $params[':search'] = "%$search%";
            $params[':search_phone'] = "%$search%";
        }

        // Apply filters
        if (!empty($filters['barber'])) {
            $whereClause .= " AND barbeiro = :barber";
            $params[':barber'] = $filters['barber'];
        }

        if (!empty($filters['status'])) {
            $whereClause .= " AND estado = :status";
            $params[':status'] = $filters['status'];
        }

        // Special filter for "Action Needed" (Past but still marked)
        if (!empty($filters['pending_action'])) {
             // Logic: Status is 'marcada' AND (Date < Now OR (Date == Now AND Time < Now))
             $whereClause .= " AND estado = 'marcada' AND (data_marcacao < CURDATE() OR (data_marcacao = CURDATE() AND horario_marcacao < CURTIME()))";
        }

        if (!empty($filters['date_start'])) {
            $whereClause .= " AND data_marcacao >= :date_start";
            $params[':date_start'] = $filters['date_start'];
        }

        if (!empty($filters['date_end'])) {
            $whereClause .= " AND data_marcacao <= :date_end";
            $params[':date_end'] = $filters['date_end'];
        }

        // Count total for pagination
        $countSql = "SELECT COUNT(*) FROM marcacoes $whereClause";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();

        // Fetch data
        $sql = "SELECT * FROM marcacoes 
                $whereClause 
                ORDER BY data_marcacao DESC, horario_marcacao DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        // Bind parameters manually due to LIMIT/OFFSET needing integers
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return [
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'totalPages' => ceil($total / $limit)
        ];
    }
    /**
     * Get unique appointment by ID.
     */
    public function getById($id) {
        $sql = "SELECT * FROM marcacoes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get client history (last 5 appointments).
     */
    public function getHistory($name, $phone, $currentId) {
        $sql = "SELECT * FROM marcacoes 
                WHERE (telefone_utilizador = :phone OR nome_utilizador = :name)
                AND id != :id
                ORDER BY data_marcacao DESC, horario_marcacao DESC
                LIMIT 5";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':phone' => $phone,
            ':name' => $name,
            ':id' => $currentId
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
