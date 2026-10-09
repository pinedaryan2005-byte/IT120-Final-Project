<?php
class ServiceModel {
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }

    public function getMainServices() {
        $sql = "SELECT * FROM catalog_items WHERE type IN ('consultation', 'installation', 'maintenance')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveQuoteRequest($data) {
        $sql = "INSERT INTO service_requests (user_id, service_id, notes, status) VALUES (:user_id, :service_id, :notes, 'Pending')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id'    => $data['user_id'] ?? null,
            ':service_id' => $data['service_id'],
            ':notes'      => $data['notes']
        ]);
    }
}
?>