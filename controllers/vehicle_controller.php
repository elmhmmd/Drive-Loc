<?php
session_start();
require_once '../classes/vehicle.php';
require_once '../classes/database.php';

$vehicle = new Vehicle();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'add':
            $vehicles_data = []; // Array to hold data for multiple vehicles
            if (isset($_FILES['picture']) && is_array($_FILES['picture']['error'])) {
                $uploadDir = '../assets/images/';

                foreach ($_FILES['picture']['tmp_name'] as $key => $tmp_name) {
                    $filename = null; // Initialize filename for each vehicle

                    if ($_FILES['picture']['error'][$key] === UPLOAD_ERR_OK) {
                        $filename_base = uniqid() . '_' . pathinfo($_FILES['picture']['name'][$key], PATHINFO_FILENAME);
                        $imageFileType = strtolower(pathinfo($_FILES['picture']['name'][$key], PATHINFO_EXTENSION));
                        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

                        if (in_array($imageFileType, $allowedTypes)) {
                            $filename = $filename_base . '.' . $imageFileType;
                            $uploadFile = $uploadDir . $filename;

                            if (!move_uploaded_file($tmp_name, $uploadFile)) {
                                $_SESSION['error'] = "Failed to upload picture for " . $_POST['vehicle_name'][$key];
                                header('Location: ../pages/Admin_Dashboard.php');
                                exit();
                            }
                        } else {
                            $_SESSION['error'] = "Invalid file type for " . $_POST['vehicle_name'][$key];
                            header('Location: ../pages/Admin_Dashboard.php');
                            exit();
                        }
                    } elseif ($_FILES['picture']['error'][$key] !== UPLOAD_ERR_NO_FILE) {
                        // Handle other upload errors
                        $_SESSION['error'] = "Error uploading picture for " . $_POST['vehicle_name'][$key] . ": " . $_FILES['picture']['error'][$key];
                        header('Location: ../pages/Admin_Dashboard.php');
                        exit();
                    }
                    // Collect data for each vehicle
                    $vehicles_data[] = [
                        'vehicle_name' => $_POST['vehicle_name'][$key],
                        'model' => $_POST['model'][$key],
                        'price' => $_POST['price'][$key],
                        'category_id' => $_POST['category_id'][$key],
                        'picture' => $filename, // Filename will be null if no file or upload error
                    ];
                }
            } else {
                // Handle case where no pictures were uploaded at all
                foreach ($_POST['vehicle_name'] as $key => $vehicle_name) {
                    $vehicles_data[] = [
                        'vehicle_name' => $vehicle_name,
                        'model' => $_POST['model'][$key],
                        'price' => $_POST['price'][$key],
                        'category_id' => $_POST['category_id'][$key],
                        'picture' => null,
                    ];
                }
            }

            $result = $vehicle->AddVehicles($vehicles_data); // Call the modified AddVehicles method
            if ($result) {
                $_SESSION['success'] = "Vehicle(s) added successfully";
            } else {
                $_SESSION['error'] = "Failed to add vehicle(s)";
            }
            break;

        case 'delete':
            $result = $vehicle->DeleteVehicle($_POST['vehicle_id']);
            if ($result) {
                $_SESSION['success'] = "Vehicle deleted successfully";
            } else {
                $_SESSION['error'] = "Failed to delete vehicle";
            }
            break;

        case 'edit':
            $picture = $_POST['old_picture'];
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../assets/images/';
                $filename_base = uniqid() . '_' . pathinfo($_FILES['picture']['name'], PATHINFO_FILENAME);
                $imageFileType = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));
                $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

                if (in_array($imageFileType, $allowedTypes)) {
                    $filename = $filename_base . '.' . $imageFileType;
                    $uploadFile = $uploadDir . $filename;
                    if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile)) {
                        // Delete old picture if it exists and is not the default
                        if ($picture && file_exists($uploadDir . $picture) && $picture !== 'default.png') {
                            unlink($uploadDir . $picture);
                        }
                        $picture = $filename;
                    } else {
                        $_SESSION['error'] = "Failed to upload new picture.";
                        header('Location: ../pages/Admin_Dashboard.php');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Invalid file type for new picture.";
                    header('Location: ../pages/Admin_Dashboard.php');
                    exit();
                }
            }
            $vehicleData = array_merge($_POST, ['picture' => $picture]);
            $result = $vehicle->ModifyVehicle($_POST['vehicle_id'], $vehicleData);
            if ($result) {
                $_SESSION['success'] = "Vehicle updated successfully";
            } else {
                $_SESSION['error'] = "Failed to update vehicle";
            }
            break;
    }
    header('Location: ../pages/Admin_Dashboard.php');
    exit();
}