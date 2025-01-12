<?php
require_once '../classes/database.php';
require_once '../classes/article.php';
require_once '../classes/favorite.php';
require_once '../classes/user.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to view your favorites.";
    header('Location: ../pages/login.php');
    exit();
}

$favoriteObj = new Favorite();
$articles = $favoriteObj->viewFavorites($_SESSION['user_id']);
$user = new User();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorite Articles - Drive & Loc</title>
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
                     <a href="/drive-loc v2/pages/add_article.php" class="text-sm tracking-widest hover:text-red-500 transition-colors font-medium">ADD ARTICLE</a>
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
            <h1 class="text-4xl font-bold mb-8">My Favorite Articles</h1>
            <?php if (empty($articles)): ?>
                    <p class="mb-4">No articles have been added to favorites.</p>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
               <?php foreach($articles as $article): ?>
                <div class="bg-gray-900 rounded-lg p-6 hover:scale-105 transition-transform">
                       <?php if($article['image']): ?>
                           <img src="../assets/images/<?php echo htmlspecialchars($article['image']); ?>" alt="Article Image" class="w-full h-48 object-cover rounded-md mb-4">
                        <?php endif; ?>
                    <h2 class="text-2xl font-bold mb-2"><?=htmlspecialchars($article['article_title']); ?></h2>
                    <a href="single_post.php?article_id=<?php echo $article['article_id'];?>" class="text-red-500 hover:text-red-400 transition-colors">Read More</a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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