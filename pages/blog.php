<?php
require_once '../classes/database.php';
require_once '../classes/theme.php';
require_once '../classes/tag.php';
require_once '../classes/article.php';
require_once '../classes/comment.php';
require_once '../classes/user.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to view the blog.";
    header('Location: ../pages/login.php');
    exit();
}

$themeObj = new Theme();
$tagObj = new Tag();
$articleObj = new Article();
$commentObj = new Comment();

$themes = $themeObj->viewThemes();
$tags = $tagObj->viewTags();


$selectedTheme = isset($_GET['theme']) ? $_GET['theme'] : null;
$selectedTag = isset($_GET['tag']) ? $_GET['tag'] : null;
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
$perPage = isset($_GET['per_page']) && in_array($_GET['per_page'], [5, 10, 15]) ? (int)$_GET['per_page'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$articles = [];

if ($selectedTheme) {
   if(!empty($searchKeyword)){
        $articles = $articleObj->searchArticlesByTheme($searchKeyword, $selectedTheme);
   } else {
     $articles = $articleObj->filterByTheme($selectedTheme);
   }
}
elseif ($selectedTag) {
    $articles = $articleObj->getArticlesByTag($selectedTag);
}
elseif (!empty($searchKeyword)) {
    $articles = $articleObj->searchArticles($searchKeyword);
}
 else {
     $articles = $articleObj->getArticlesWithAuthors($perPage, $offset);
}

$totalArticles = $articleObj->getTotalArticlesCount();
$totalPages = ceil($totalArticles / $perPage);



$comments = $commentObj->ViewComments();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Drive & Loc</title>
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
            <h1 class="text-4xl font-bold mb-8">Blog</h1>
             <div class="flex justify-between items-center mb-4">
                <div class="flex space-x-4">
                    <form action="" method="get" class="flex items-center space-x-2">
                         <input type="text" name="search" placeholder="Search articles" value="<?php echo htmlspecialchars($searchKeyword); ?>" class="bg-gray-800 text-white px-3 py-2 rounded">
                         <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded">Search</button>
                    </form>
                    <form action="" method="get">
                        <select name="theme" class="bg-gray-800 text-white px-3 py-2 rounded" onchange="this.form.submit()">
                            <option value="">Filter by Theme</option>
                            <?php foreach($themes as $theme): ?>
                                <option value="<?php echo $theme['theme_id']; ?>" <?php if($selectedTheme == $theme['theme_id']) echo 'selected'; ?>><?php echo htmlspecialchars($theme['theme_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                         </form>
                         <form action="" method="get">
                        <select name="tag" class="bg-gray-800 text-white px-3 py-2 rounded" onchange="this.form.submit()">
                            <option value="">Filter by Tag</option>
                            <?php foreach($tags as $tag): ?>
                                 <option value="<?php echo $tag['tag_name']; ?>" <?php if($selectedTag == $tag['tag_name']) echo 'selected'; ?>><?php echo htmlspecialchars($tag['tag_name']); ?></option>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php if(empty($articles)): ?>
                    <p>No articles found</p>
                <?php else: ?>
                <?php foreach($articles as $article): ?>
                 <div class="bg-gray-900 rounded-lg p-6 hover:scale-105 transition-transform">
                      <?php if($article['image']): ?>
                         <img src="../assets/images/<?php echo htmlspecialchars($article['image']); ?>" alt="Article Image" class="w-full h-48 object-cover rounded-md mb-4">
                     <?php endif; ?>
                    <h2 class="text-2xl font-bold mb-2"><?=htmlspecialchars($article['article_title']); ?></h2>
                    <p class="text-gray-400 mb-4">By <?php echo htmlspecialchars($article['username']); ?></p>
                    <a href="single_post.php?article_id=<?php echo $article['article_id'];?>" class="text-red-500 hover:text-red-400 transition-colors">Read More</a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
               <?php if ($totalPages > 1): ?>
                 <div class="flex justify-center mt-8">
                      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                           <a href="?page=<?php echo $i; ?>&per_page=<?php echo $perPage; ?>&search=<?php echo htmlspecialchars($searchKeyword); ?>&theme=<?php echo htmlspecialchars($selectedTheme);?>&tag=<?php echo htmlspecialchars($selectedTag);?>" class="px-4 py-2 mx-1 rounded <?php echo $page == $i ? 'bg-red-600 text-white' : 'bg-gray-800 hover:bg-gray-700 text-white'; ?>">
                                 <?php echo $i; ?>
                           </a>
                        <?php endfor; ?>
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