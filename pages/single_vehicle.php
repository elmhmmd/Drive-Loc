<?php
require_once '../classes/database.php';
require_once '../classes/vehicle.php';
require_once '../classes/category.php';
require_once '../classes/reservation.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to view vehicle details and make reservations.";
    header('Location: ../pages/login.php');
    exit();
}

$vehicleObj = new Vehicle();
$reservationObj = new Reservation();


$vehicle_id = isset($_GET['vehicle_id']) ? (int)$_GET['vehicle_id'] : null;

if (!$vehicle_id) {
    $_SESSION['error'] = "Invalid vehicle ID.";
    header('Location: user_page.php');
    exit();
}


$vehicle = $vehicleObj->ShowVehicleDetails($vehicle_id);

if (!$vehicle) {
    $_SESSION['error'] = "Vehicle not found.";
    header('Location: user_page.php');
    exit();
}

$isAvailable = true;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_availability'])) {
    $checkFromDate = $_POST['from_date'];
    $checkToDate = $_POST['to_date'];
    $isAvailable = $reservationObj->isVehicleAvailable($vehicle_id, $checkFromDate, $checkToDate);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve_vehicle']) && $isAvailable) {
    $reservation_data = [
        'from_date' => $_POST['from_date'],
        'to_date' => $_POST['to_date'],
        'pickup_location' => $_POST['pickup_location'],
        'return_location' => $_POST['return_location'],
        'client_id' => $_SESSION['user_id'],
        'vehicle_id' => $vehicle_id,
    ];


      if($reservationObj->RentVehicle($reservation_data)) {
             $_SESSION['success'] = "Vehicle reserved successfully!";
            header('Location: user_page.php');
             exit();
        } else {
            $_SESSION['error'] = "Failed to make the reservation.";
        }


}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($vehicle['vehicle_name']); ?> Details - Drive & Loc</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Space Grotesk', sans-serif;
        }

        .car-gradient {
            background: linear-gradient(90deg, #FF0000 0%, #000000 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-black text-white overflow-x-hidden">
    <nav class="bg-black fixed w-full z-50 top-0">
        <div class="mx-8 md:mx-16 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-12 bg-red-600"></div>
                      <a href="/drive-loc v2/pages/Homepage.php" class="text-2xl font-bold tracking-wider">Drive & Loc</a>
                </div>
                  <div class="flex items-center gap-8">
                     <a href="/drive-loc v2/pages/blog.php" class="text-sm tracking-widest hover:text-red-500 transition-colors font-medium">BLOG</a>
                     <a href="/drive-loc v2/pages/favorites.php" class="text-sm tracking-widest hover:text-red-500 transition-colors font-medium">FAVORITES</a>
                    <a href="/drive-loc v2/controllers/logout.php" class="text-sm tracking-widest hover:text-red-500 transition-colors font-medium">LOG OUT</a>
                 </div>
            </div>
        </div>
    </nav>

    <section class="py-32 mt-16 mx-8 md:mx-16">
        <div class="max-w-3xl mx-auto">
         <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded-lg mb-6">' .
                         htmlspecialchars($_SESSION['error']) .
                         '</div>';
                    unset($_SESSION['error']);
                }
           if (isset($_SESSION['success'])) {
                echo '<div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded-lg mb-6">' . 
                     htmlspecialchars($_SESSION['success']) . 
                     '</div>';
                unset($_SESSION['success']);
            }
                ?>
            <h1 class="text-4xl font-bold mb-6"><?php echo htmlspecialchars($vehicle['vehicle_name']); ?></h1>
             <?php if($vehicle['picture']): ?>
              <img src="../assets/images/<?php echo htmlspecialchars($vehicle['picture']); ?>" alt="Vehicle Image" class="w-full h-96 object-cover rounded-md mb-8">
              <?php endif; ?>
            <div class="mb-8">
                <p class="text-gray-300 mb-2">
                    <span class="font-medium">Model:</span> <?php echo htmlspecialchars($vehicle['model']); ?>
                </p>
                <p class="text-gray-300">
                    <span class="font-medium">Price:</span> $<?php echo htmlspecialchars($vehicle['price']); ?>
                </p>
            </div>

            <div class="bg-gray-900 p-6 rounded-lg">
                 <h2 class="text-2xl font-bold mb-4">Reserve This Vehicle</h2>
                  <?php
                   if($isAvailable): ?>
                      <div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded-lg mb-6">
                          Available for reservation
                         </div>
                  <?php else: ?>
                       <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded-lg mb-6">
                             Not available for the specified dates.
                       </div>
                   <?php endif; ?>
                <form method="post" action="/drive-loc v2/pages/single_vehicle.php?vehicle_id=<?php echo $vehicle_id; ?>" class="space-y-4">
                   <div>
                        <label for="from_date" class="block text-gray-300 mb-1">From Date</label>
                        <input type="datetime-local" id="from_date" name="from_date" class="bg-gray-800 text-white px-4 py-2 rounded w-full" required>
                    </div>
                    <div>
                        <label for="to_date" class="block text-gray-300 mb-1">To Date</label>
                        <input type="datetime-local" id="to_date" name="to_date" class="bg-gray-800 text-white px-4 py-2 rounded w-full" required>
                    </div>
                    <button type="submit" name="check_availability" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Check Availability</button>
                   <?php if($isAvailable): ?>
                     <div>
                         <label for="pickup_location" class="block text-gray-300 mb-1">Pickup Location</label>
                         <input type="text" id="pickup_location" name="pickup_location" class="bg-gray-800 text-white px-4 py-2 rounded w-full" required>
                    </div>
                   <div>
                         <label for="return_location" class="block text-gray-300 mb-1">Return Location</label>
                         <input type="text" id="return_location" name="return_location" class="bg-gray-800 text-white px-4 py-2 rounded w-full" required>
                   </div>
                   <button type="submit" name="reserve_vehicle" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Reserve Vehicle</button>
                   <?php endif; ?>
                </form>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 py-12">
        <div class="mx-8 md:mx-16 text-center text-gray-400">
            © <?php echo date("Y"); ?> Drive & Loc. All rights reserved.
        </div>
    </footer>
    <script>
          document.addEventListener('DOMContentLoaded', () => {
           // Smooth scroll animation for navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>
</html>