<?php
date_default_timezone_set('Europe/Lisbon');

class BookingUtils {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAvailableTimes($barber, $date) {
        // Converter data para o formato do banco de dados
        $dateParts = explode('-', $date);
        if (count($dateParts) !== 3) {
            return ['success' => false, 'message' => 'O formato da data é inválido.'];
        }
        $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];

        // Buscar horários ocupados (marcações e bloqueios temporários)
        $sql = "SELECT horario_marcacao FROM marcacoes 
                WHERE barbeiro = :barber AND data_marcacao = :date AND estado = 'marcada'
                UNION
                SELECT time_slot FROM temporary_locks 
                WHERE barber = :barber AND date = :date AND expires_at > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':barber', $barber);
        $stmt->bindParam(':date', $formattedDate);
        $stmt->execute();
        $bookedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        // Gerar horários disponíveis (das 9h às 19h)
        $availableTimes = [];
        for ($hour = 9; $hour < 19; $hour++) {
            $availableTimes[] = sprintf('%02d:00', $hour);
            $availableTimes[] = sprintf('%02d:30', $hour);
        }

        // Remover horários ocupados
        $availableTimes = array_diff($availableTimes, $bookedTimes);
        return array_values($availableTimes);
    }

    public function lockTimeSlot($barber, $date, $time) {
        $dateParts = explode('-', $date);
        if (count($dateParts) !== 3) {
            return ['success' => false, 'message' => 'O formato da data é inválido.'];
        }
        $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];

        // Verificar se o horário já está ocupado
        $checkSql = "SELECT COUNT(*) FROM marcacoes 
                     WHERE barbeiro = :barber AND data_marcacao = :date AND horario_marcacao = :time AND estado = 'marcada'
                     UNION
                     SELECT COUNT(*) FROM temporary_locks 
                     WHERE barber = :barber AND date = :date AND time_slot = :time AND expires_at > NOW()";
        $stmt = $this->pdo->prepare($checkSql);
        $stmt->bindParam(':barber', $barber);
        $stmt->bindParam(':date', $formattedDate);
        $stmt->bindParam(':time', $time);
        $stmt->execute();
        $count = array_sum($stmt->fetchAll(PDO::FETCH_COLUMN, 0));

        if ($count > 0) {
            return ['success' => false, 'message' => 'Horário já reservado.'];
        }

        // Criar bloqueio temporário (5 minutos)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        $sql = "INSERT INTO temporary_locks (barber, date, time_slot, expires_at) 
                VALUES (:barber, :date, :time, :expires_at)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':barber', $barber);
        $stmt->bindParam(':date', $formattedDate);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':expires_at', $expiresAt);
        $stmt->execute();

        $lockId = $this->pdo->lastInsertId();
        return ['success' => true, 'lock_id' => $lockId];
    }

    public function releaseTimeSlot($lockId) {
        $sql = "DELETE FROM temporary_locks WHERE id = :lock_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':lock_id', $lockId);
        $stmt->execute();
    }

    public function isTimeSlotAvailable($barber, $date, $time) {
        $dateParts = explode('-', $date);
        if (count($dateParts) !== 3) {
            return false;
        }
        $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];

        $sql = "SELECT COUNT(*) FROM marcacoes 
                WHERE barbeiro = :barber AND data_marcacao = :date AND horario_marcacao = :time AND estado = 'marcada'
                UNION
                SELECT COUNT(*) FROM temporary_locks 
                WHERE barber = :barber AND date = :date AND time_slot = :time AND expires_at > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':barber', $barber);
        $stmt->bindParam(':date', $formattedDate);
        $stmt->bindParam(':time', $time);
        $stmt->execute();
        $count = array_sum($stmt->fetchAll(PDO::FETCH_COLUMN, 0));

        return $count === 0;
    }
}