<?php

class Vehicle {
    private $vehicle_id;
    private $vehicle_name;
    private $model;
    private $price;
    private $category_id;
    private $picture;
    private $reservation_id;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Getters
    public function getVehicleId() {
        return $this->vehicle_id;
    }

    public function getVehicleName() {
        return $this->vehicle_name;
    }

    public function getModel() {
        return $this->model;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getCategoryId() {
        return $this->category_id;
    }

    public function getPicture() {
        return $this->picture;
    }

    public function getReservationId() {
        return $this->reservation_id;
    }

    // Setters
    public function setVehicleId($vehicle_id) {
        $this->vehicle_id = $vehicle_id;
    }

    public function setVehicleName($vehicle_name) {
        $this->vehicle_name = $vehicle_name;
    }

    public function setModel($model) {
        $this->model = $model;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setCategoryId($category_id) {
        $this->category_id = $category_id;
    }

    public function setPicture($picture) {
        $this->picture = $picture;
    }

    public function setReservationId($reservation_id) {
        $this->reservation_id = $reservation_id;
    }

    public function ShowVehicles($limit = null, $offset = null) {
           $query = "SELECT * FROM vehicles";
            if ($limit !== null && $offset !== null) {
                $query .= " LIMIT :limit OFFSET :offset";
            }
            $stmt = $this->db->prepare($query);
            if ($limit !== null && $offset !== null) {
                 $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
            }
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
        $query = "SELECT * FROM vehicles WHERE vehicle_name LIKE ? OR model LIKE ? OR price LIKE ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function filterVehiclesByCategory($category_id, $limit = null, $offset = null) {
        $query = "SELECT v.* FROM vehicles v 
                JOIN categories c ON v.category_id = c.category_id 
                WHERE c.category_id = :category_id";
             if ($limit !== null && $offset !== null) {
                 $query .= " LIMIT :limit OFFSET :offset";
          }
         $stmt = $this->db->prepare($query);
          if ($limit !== null && $offset !== null) {
                $stmt->bindValue(':category_id', (int) $category_id, PDO::PARAM_INT);
                   $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
                   $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
                    $stmt->execute();
               } else{
                    $stmt->bindValue(':category_id', (int) $category_id, PDO::PARAM_INT);
                   $stmt->execute();
               }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

    // Modified to handle an array of vehicle data
    public function AddVehicles($vehicles_data) {
        $success = true;
        foreach ($vehicles_data as $data) {
            $query = "INSERT INTO vehicles (vehicle_name, model, price, category_id, picture)
                     VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([
                $data['vehicle_name'],
                $data['model'],
                $data['price'],
                $data['category_id'],
                $data['picture']
            ]);
            if (!$result) {
                $success = false;
            }
        }
        return $success;
    }

    public function ModifyVehicle($id, $data) {
        $query = "UPDATE vehicles
                 SET vehicle_name = ?,
                     model = ?,
                     price = ?,
                     picture = ?,
                     category_id = ?
                 WHERE vehicle_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['vehicle_name'],
            $data['model'],
            $data['price'],
            $data['picture'],
            $data['category_id'],
            $id
        ]);
    }

    public function DeleteVehicle($id) {
        $query = "DELETE FROM vehicles WHERE vehicle_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
     public function getTotalVehiclesCount() {
            $query = "SELECT COUNT(*) FROM vehicles";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
}