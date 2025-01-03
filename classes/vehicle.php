<?php

class Vehicle {
    private $vehicle_id;
    private $vehicle_name;
    private $model;
    private $price;
    private $availability;
    private $category_id;
    private $reservation_id;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    } 

    public function ShowVehicles() {
        $query = "SELECT * FROM vehicles";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ShowVehicleDetails($id) {
        $query = "SELECT * FROM vehicles WHERE vehicle_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function SearchVehicles($keyword) {
        $query = "SELECT * FROM vehicles WHERE vehicle_name LIKE ? OR model LIKE ? OR price LIKE ?"; //maybe delineate searching based on different attributes (and add availability, basically make it a filter)
        $stmt = $this->db->prepare($query);
        $stmt->execute(["%$keyword%", "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function AddVehicle($data) {
        $query = "INSERT INTO vehicles (vehicle_name, model, price, availability) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['vehicle_name'],
            $data['model'],
            $data['price'],
            $data['availability']
        ]);
    }

    public function ModifyVehicle($id, $data) {
        $query = "UPDATE vehicles SET vehicle_name = ?, model = ?, price = ?, availability = ? WHERE vehicle_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['vehicle_name'],
            $data['model'],
            $data['price'],
            $data['availability'],
            $id
        ]);
    }

    public function DeleteVehicle($id) {
        $query = "DELETE FROM vehicles WHERE vehicle_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}
