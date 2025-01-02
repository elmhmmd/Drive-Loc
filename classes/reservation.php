<?php

class Reservation {
    private $reservation_id;
    private $from_date;
    private $to_date;
    private $location;
    private $client_id;
    private $vehicle_id;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function RentVehicle($data) {
        $query = "INSERT INTO reservations (from_date, to_date, location, client_id, vehicle_id) 
                 VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['from_date'],
            $data['to_date'],
            $data['location'],
            $data['client_id'],
            $data['vehicle_id']
        ]);
    }

    public function ViewReservations($client_id = null) {
        $query = "SELECT r.*, v.vehicle_name 
                 FROM reservations r 
                 JOIN vehicles v ON r.vehicle_id = v.vehicle_id";
        
        if ($client_id) {
            $query .= " WHERE r.client_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$client_id]);
        } else {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function DeleteReservations($id) {
        $query = "DELETE FROM reservations WHERE reservation_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}
