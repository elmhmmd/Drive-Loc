<?php
require_once '../classes/database.php';
require_once '../classes/vehicle.php';
require_once '../classes/category.php';
require_once '../classes/review.php';
require_once '../classes/user.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to access the user page.";
    header('Location: ../pages/login.php');
    exit();
}


$vehicle = new Vehicle();
$category = new Category();
$review = new Review();


$categories = $category->viewCategories();
$userReviews = $review->viewUserReviews($_SESSION['user_id']);


$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
$perPage = isset($_GET['per_page']) && in_array($_GET['per_page'], [5, 10, 15]) ? (int)$_GET['per_page'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;
$vehicles = [];


if ($selectedCategory) {
        $vehicles = $vehicle->filterVehiclesByCategory($selectedCategory, $perPage, $offset);
} elseif (!empty($searchKeyword)) {
        $vehicles = $vehicle->SearchVehicles($searchKeyword);
} else {
        $vehicles = $vehicle->ShowVehicles($perPage, $offset);
}

$totalVehicles = $vehicle->getTotalVehiclesCount();
$totalPages = ceil($totalVehicles / $perPage);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review'])) {
        $review_id = $_POST['review_id'];
         if (!isset($_SESSION['user_id'])) {
               $_SESSION['error'] = "You must be logged in to delete reviews.";
                header('Location: ../pages/login.php');
                  exit();
                }
            $reviewToDelete = $review->getReviewById($review_id);
             if($reviewToDelete['user_id'] === $_SESSION['user_id']){
                   if($review->DeleteReview($review_id)){
                   } else {
                   }
                 }
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_review'])) {
              $review_id = $_POST['review_id'];
                 $edited_review_content = trim($_POST['edit_review_content']);
                 $edited_review_rating = trim($_POST['edit_review_rating']);
                  if (empty($edited_review_content)) {
                      $_SESSION['error'] = "Review cannot be empty.";
                     }
                    if (!isset($_SESSION['user_id'])) {
                      $_SESSION['error'] = "You must be logged in to edit reviews.";
                         header('Location: ../pages/login.php');
                         exit();
                    }
                    $reviewToEdit = $review->getReviewById($review_id);
                     if($reviewToEdit['user_id'] === $_SESSION['user_id']){
                        if($review->modifyReview($review_id, $edited_review_content, $edited_review_rating)){
                            } else {
                            }
                        }
         }
    
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
                $review_content = trim($_POST['review_content']);
                  $rating = $_POST['rating'];
                 if (empty($review_content)) {
                      $_SESSION['error'] = "Review cannot be empty";
                } else if (!isset($_SESSION['user_id'])) {
                        $_SESSION['error'] = "You must be logged in to add a review.";
                         header('Location: ../pages/login.php');
                        exit();
                    } else {
                           $reviewData = [
                                'user_id' => $_SESSION['user_id'],
                               'content' => $review_content,
                                'rating' => $rating
                           ];

                            if($review->addReview($reviewData)){

                             } else {
                             }

                 }
       }
      $userReviews = $review->viewUserReviews($_SESSION['user_id'], $perPage, $offset );
      $totalReviews = $review->getTotalReviewsCount($_SESSION['user_id']);
      $totalPagesReviews = ceil($totalReviews / $perPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page - Drive & Loc</title>
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
        <div class="max-w-5xl mx-auto">
             <?php
             if (isset($_SESSION['success'])) {
                echo '<div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded-lg mb-6">' . 
                     htmlspecialchars($_SESSION['success']) . 
                     '</div>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded-lg mb-6">' . 
                     htmlspecialchars($_SESSION['error']) . 
                     '</div>';
                unset($_SESSION['error']);
            }
            ?>
            <h1 class="text-4xl font-bold mb-8">Welcome, <?php echo (new User())->getUserById($_SESSION['user_id'])['username']; ?></h1>
             <div class="flex justify-between items-center mb-4">
                <div class="flex space-x-4">
                   <form action="" method="get" class="flex items-center space-x-2">
                         <input type="text" name="search" placeholder="Search vehicles" value="<?php echo htmlspecialchars($searchKeyword); ?>" class="bg-gray-800 text-white px-3 py-2 rounded">
                         <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded">Search</button>
                   </form>
                    <form action="" method="get">
                         <select name="category" class="bg-gray-800 text-white px-3 py-2 rounded" onchange="this.form.submit()">
                            <option value="">Filter by Category</option>
                            <?php foreach($categories as $cat): ?>
                                 <option value="<?php echo $cat['category_id']; ?>" <?php if($selectedCategory == $cat['category_id']) echo 'selected'; ?>><?php echo htmlspecialchars($cat['category_name']); ?></option>
                           <?php endforeach; ?>
                        </select>
                    </form>
                 </div>
                 <form action="" method="get">
                     <select name="per_page" class="bg-gray-800 text-white px-3 py-2 rounded" onchange="this.form.submit()">
                        <option value="5" <?php if ($perPage == 5) echo 'selected'; ?>>5 per page</option>
                        <option value="10" <?php if ($perPage == 10) echo 'selected'; ?>>10 per page</option>
                        <option value="15" <?php if ($perPage == 15) echo 'selected'; ?>>15 per page</option>
                     </select>
                  </form>
            </div>
            <h2 class="text-3xl font-bold mb-6">Available Vehicles</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                   <?php if(empty($vehicles)): ?>
                        <p>No vehicles found.</p>
                    <?php else: ?>
                    <?php foreach($vehicles as $vehicle): ?>
                         <div class="bg-gray-900 rounded-lg p-6 hover:scale-105 transition-transform">
                              <?php if($vehicle['picture']): ?>
                               <img src="../assets/images/<?php echo htmlspecialchars($vehicle['picture']); ?>" alt="Vehicle Image" class="w-full h-48 object-cover rounded-md mb-4">
                              <?php endif; ?>
                              <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($vehicle['vehicle_name']); ?></h2>
                              <p class="text-gray-400 mb-2">Model: <?php echo htmlspecialchars($vehicle['model']); ?></p>
                              <p class="text-gray-400 mb-4">Price: $<?php echo htmlspecialchars($vehicle['price']); ?></p>
                             <a href="single_vehicle.php?vehicle_id=<?php echo $vehicle['vehicle_id']; ?>" class="text-red-500 hover:text-red-400 transition-colors">View Details</a>
                         </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
              <?php if ($totalPages > 1): ?>
                 <div class="flex justify-center mt-8">
                      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                           <a href="?page=<?php echo $i; ?>&per_page=<?php echo $perPage; ?>&search=<?php echo htmlspecialchars($searchKeyword); ?>&category=<?php echo htmlspecialchars($selectedCategory);?>" class="px-4 py-2 mx-1 rounded <?php echo $page == $i ? 'bg-red-600 text-white' : 'bg-gray-800 hover:bg-gray-700 text-white'; ?>">
                                 <?php echo $i; ?>
                           </a>
                        <?php endfor; ?>
                   </div>
               <?php endif; ?>
                <h2 class="text-3xl font-bold mt-12 mb-6">My Reviews</h2>
                 <?php if (empty($userReviews)): ?>
                     <p>No reviews found.</p>
                    <?php else: ?>
                         <?php foreach ($userReviews as $userReview): ?>
                         <div class="bg-gray-900 p-4 rounded-lg mb-4">
                             <form action="" method="post" class="mt-1">
                                    <input type="hidden" name="review_id" value="<?php echo $userReview['review_id']; ?>">
                                   <div id="review-text-<?php echo $userReview['review_id']; ?>">
                                         <p class="mt-1 inline-block">Rating: <?php echo htmlspecialchars($userReview['rating']); ?>/5</p>
                                        <p class="mt-1 inline-block"><?php echo htmlspecialchars($userReview['content']); ?></p>
                                          <button type="button" onclick="editReview('<?php echo $userReview['review_id']; ?>')" class="bg-yellow-500 hover:bg-yellow-700 text-black font-bold py-1 px-2 rounded inline-block">
                                                  Edit
                                             </button>
                                             <button type="submit" name="delete_review" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded inline-block">Delete</button>
                                        </div>
                                      <div id="review-edit-<?php echo $userReview['review_id']; ?>" style="display: none;">
                                          <textarea name="edit_review_content" class="bg-gray-800 text-white px-4 py-2 rounded w-full inline-block" ><?php echo htmlspecialchars($userReview['content']); ?></textarea>
                                             <input type="number" name="edit_review_rating" min="1" max="5" value="<?php echo htmlspecialchars($userReview['rating']); ?>"  class="bg-gray-800 text-white px-3 py-2 rounded w-16 inline-block">/5
                                            <button type="submit" name="edit_review" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded inline-block">Save</button>
                                             <button type="button" onclick="cancelReviewEdit('<?php echo $userReview['review_id']; ?>')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-2 rounded inline-block">Cancel</button>
                                         </div>
                                 </form>
                         </div>
                       <?php endforeach; ?>
                    <?php endif; ?>
                   <?php if ($totalPagesReviews > 1): ?>
                 <div class="flex justify-center mt-8">
                      <?php for ($i = 1; $i <= $totalPagesReviews; $i++): ?>
                           <a href="?page=<?php echo $i; ?>&per_page=<?php echo $perPage; ?>&search=<?php echo htmlspecialchars($searchKeyword); ?>&category=<?php echo htmlspecialchars($selectedCategory);?>" class="px-4 py-2 mx-1 rounded <?php echo $page == $i ? 'bg-red-600 text-white' : 'bg-gray-800 hover:bg-gray-700 text-white'; ?>">
                                 <?php echo $i; ?>
                           </a>
                        <?php endfor; ?>
                   </div>
               <?php endif; ?>
              <h2 class="text-3xl font-bold mt-12 mb-6">Add Review</h2>
                 <form  method="post">
                     <textarea name="review_content" placeholder="Add your review..." class="bg-gray-800 text-white px-4 py-2 rounded w-full"></textarea>
                     <input type="number" name="rating" placeholder="Rating (1-5)" min="1" max="5"  class="bg-gray-800 text-white px-3 py-2 rounded w-16" required >/5
                    <button type="submit" name="add_review" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-2">Post Review</button>
                </form>
                
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
       function editReview(reviewId) {
            document.getElementById('review-text-' + reviewId).style.display = 'none';
            document.getElementById('review-edit-' + reviewId).style.display = 'block';
          }
          function cancelReviewEdit(reviewId) {
               document.getElementById('review-text-' + reviewId).style.display = 'block';
              document.getElementById('review-edit-' + reviewId).style.display = 'none';
          }
    </script>
</body>
</html>