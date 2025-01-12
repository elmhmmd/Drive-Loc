<?php

class Reservation {
    private $reservation_id;
    private $from_date;
    private $to_date;
    private $location;
    private $pickup_location;
    private $return_location;
    private $client_id;
    private $vehicle_id;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    
    // Getters
    public function getReservationId() {
        return $this->reservation_id;
    }

    public function getFromDate() {
        return $this->from_date;
    }

    public function getToDate() {
        return $this->to_date;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getPickupLocation() {
        return $this->pickup_location;
    }

    public function getReturnLocation() {
        return $this->return_location;
    }

    public function getClientId() {
        return $this->client_id;
    }

    public function getVehicleId() {
        return $this->vehicle_id;
    }

    // Setters
    public function setReservationId($reservation_id) {
        $this->reservation_id = $reservation_id;
    }

    public function setFromDate($from_date) {
        $this->from_date = $from_date;
    }

    public function setToDate($to_date) {
        $this->to_date = $to_date;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function setPickupLocation($pickup_location) {
        $this->pickup_location = $pickup_location;
    }

    public function setReturnLocation($return_location) {
        $this->return_location = $return_location;
    }

    public function setClientId($client_id) {
        $this->client_id = $client_id;
    }

    public function setVehicleId($vehicle_id) {
        $this->vehicle_id = $vehicle_id;
    }


     public function RentVehicle($data) {
         // Check for existing reservations
        if (!$this->isVehicleAvailable($data['vehicle_id'], $data['from_date'], $data['to_date'])) {
            return false;
        }
         
        $query = "INSERT INTO reservations (from_date, to_date, location, pickup_location, return_location, client_id, vehicle_id) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['from_date'],
            $data['to_date'],
            $data['location'],
            $data['pickup_location'],
            $data['return_location'],
            $data['client_id'],
            $data['vehicle_id']
        ]);
    }
    
    private function isVehicleAvailable($vehicleId, $fromDate, $toDate) {
    $query = "SELECT COUNT(*) FROM reservations
              WHERE vehicle_id = :vehicle_id
              AND (
                  (:from_date < to_date AND :to_date > from_date)
              )";


        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':vehicle_id', $vehicleId, PDO::PARAM_INT);
        $stmt->bindValue(':from_date', $fromDate, PDO::PARAM_STR);
        $stmt->bindValue(':to_date', $toDate, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() == 0;
    }

    public function ViewReservations($client_id = null) {
        $query = "SELECT r.*, v.vehicle_name, r.pickup_location, r.return_location 
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