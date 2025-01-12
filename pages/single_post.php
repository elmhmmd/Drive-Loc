<?php
    require_once '../classes/Database.php';
    require_once '../classes/article.php';
    require_once '../classes/theme.php';
    require_once '../classes/tag.php';
     require_once '../classes/comment.php';
     require_once '../classes/user.php';
    require_once '../classes/favorite.php';
session_start();

    $articleObj = new Article();
    $theme = new Theme();
    $tag = new Tag();
    $comment = new Comment();
     $user = new User();
     $favorite = new Favorite();

    $article_id = isset($_GET['article_id']) ? $_GET['article_id'] : null;
    if(!$article_id) {
      header('Location: blog.php');
       exit();
    }

    $articleData = $articleObj->getArticleById($article_id);
     if(!$articleData) {
        header('Location: blog.php');
       exit();
    }
      $isFavorited = false;
     if(isset($_SESSION['user_id'])) {
         $isFavorited = $favorite->isFavorited($_SESSION['user_id'], $article_id);
     }
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           if (isset($_POST['favorite_article'])) {
               if (!isset($_SESSION['user_id'])) {
                   $_SESSION['error'] = "You must be logged in to add favorites.";
                   header('Location: ../pages/login.php');
                    exit();
                }
               if($favorite->addFavorite($_SESSION['user_id'], $article_id)){
                   $isFavorited = true;
                } else {
                      $_SESSION['error'] = "Error adding to favorites.";
                 }
             }
             if (isset($_POST['unfavorite_article'])) {
                  if (!isset($_SESSION['user_id'])) {
                       $_SESSION['error'] = "You must be logged in to remove from favorites.";
                        header('Location: ../pages/login.php');
                        exit();
                   }
                 if($favorite->removeFavorite($_SESSION['user_id'], $article_id)){
                       $isFavorited = false;
                     } else {
                           $_SESSION['error'] = "Error removing from favorites.";
                     }
            }
            if (isset($_POST['comment_content'])) {
                $comment_content = trim($_POST['comment_content']);
                 if (empty($comment_content)) {
                      $_SESSION['error'] = "Comment cannot be empty";
                } else {
                    if (!isset($_SESSION['user_id'])) {
                        $_SESSION['error'] = "You must be logged in to comment.";
                        header('Location: ../pages/login.php');
                         exit();
                    }
                     if($comment->addComment($comment_content, $article_id, $_SESSION['user_id'])){
                        // Success Message
                     } else {
                       // Error Message
                    }
                }
            }
            if (isset($_POST['delete_comment'])) {
                $comment_id = $_POST['comment_id'];
                if (!isset($_SESSION['user_id'])) {
                    $_SESSION['error'] = "You must be logged in to delete comments.";
                     header('Location: ../pages/login.php');
                    exit();
                }
                $commentToDelete = $comment->getCommentById($comment_id);
                if($commentToDelete['user_id'] === $_SESSION['user_id']){
                    if($comment->DeleteComment($comment_id)){

                    } else {

                    }
                }
            }
             if (isset($_POST['edit_comment'])) {
                $comment_id = $_POST['comment_id'];
                $edited_comment_content = trim($_POST['edit_comment_content']);
                   if (empty($edited_comment_content)) {
                       $_SESSION['error'] = "Comment cannot be empty.";
                     } else {
                    if (!isset($_SESSION['user_id'])) {
                      $_SESSION['error'] = "You must be logged in to edit comments.";
                         header('Location: ../pages/login.php');
                         exit();
                    }
                    $commentToEdit = $comment->getCommentById($comment_id);
                     if($commentToEdit['user_id'] === $_SESSION['user_id']){
                        if($comment->modifyComment($comment_id, $edited_comment_content)){
                            } else {
                            }
                        }
                    }
            }
        }
     $comments = $comment->ViewArticleComments($article_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($articleData['article_title']); ?> - Drive & Loc</title>
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
                    <a href="/drive-loc v2/pages/login.php" class="text-sm tracking-widest hover:text-red-500 transition-colors font-medium">LOG IN</a>
                    <a href="/drive-loc v2/pages/signup.php" class="diagonal-border px-8 py-3 text-sm tracking-widest hover:opacity-90 transition-opacity"> REGISTER </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="py-32 mt-16 mx-8 md:mx-16">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold mb-4"><?php echo htmlspecialchars($articleData['article_title']); ?></h1>
             <?php if($articleData['image']): ?>
                <img src="../assets/images/<?php echo htmlspecialchars($articleData['image']); ?>" alt="Article Image" class="w-full h-96 object-cover rounded-md mb-4">
            <?php endif; ?>
            <div class="flex items-center justify-between mb-6">
                <span class="text-sm text-gray-500">By <?php echo $articleObj->fetchArticleAuthor($articleData['user_id']); ?></span>
                <div>
                    <form action="" method="post">
                        <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                        <?php if($isFavorited): ?>
                            <button type="submit" name="unfavorite_article" class="hover:text-red-500 transition-colors"><i class="fas fa-heart"></i> Remove from Favorites</button>
                        <?php else: ?>
                            <button type="submit" name="favorite_article" class="hover:text-red-500 transition-colors"><i class="far fa-heart"></i> Add to Favorites</button>
                        <?php endif; ?>
                   </form>
                </div>
            </div>

            <article class="prose prose-invert mb-8">
                <!-- Blog post content goes here -->
                 <?php echo $articleData['article_content']; ?>
                <!-- Images/Videos can be included here -->
            </article>

            <div class="mb-8">
                <h3 class="text-lg font-bold mb-2">Tags:</h3>
                <div class="flex space-x-2">
                     <?php foreach ($articleObj->getArticleTags($articleData['article_id']) as $tagData): ?>
                         <span class="bg-gray-800 text-gray-400 px-2 py-1 rounded-full text-xs"><?php echo htmlspecialchars($tagData['tag_name']); ?></span>
                     <?php endforeach; ?>
                </div>
            </div>

            <!-- Comments Section -->
            <div>
                <h3 class="text-lg font-bold mb-4">Comments</h3>
                <!-- Loop through comments here -->
                 <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $commentData): ?>
                        <div class="bg-gray-900 p-4 rounded-lg mb-4">
                            <div class="flex items-start space-x-2">
                                <div class="font-bold"><?php echo $user->getUserById($commentData['user_id']) ? htmlspecialchars($user->getUserById($commentData['user_id'])['username']) : 'Unknown'; ?></div>
                            </div>
                           <form action="" method="post" class="mt-1">
                                <input type="hidden" name="comment_id" value="<?php echo $commentData['comment_id']; ?>">
                                 <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $commentData['user_id']): ?>
                                       <div id="comment-text-<?php echo $commentData['comment_id']; ?>">
                                           <p class="mt-1 inline-block"><?php echo htmlspecialchars($commentData['comment_content']); ?></p>
                                            <button type="button" onclick="editComment('<?php echo $commentData['comment_id']; ?>')" class="bg-yellow-500 hover:bg-yellow-700 text-black font-bold py-1 px-2 rounded inline-block">
                                                   Edit
                                             </button>
                                            <button type="submit" name="delete_comment" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded inline-block">Delete</button>
                                        </div>
                                        <div id="comment-edit-<?php echo $commentData['comment_id']; ?>" style="display: none;">
                                          <textarea name="edit_comment_content" class="bg-gray-800 text-white px-4 py-2 rounded w-full inline-block" ><?php echo htmlspecialchars($commentData['comment_content']); ?></textarea>
                                            <button type="submit" name="edit_comment" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded inline-block">Save</button>
                                             <button type="button" onclick="cancelEdit('<?php echo $commentData['comment_id']; ?>')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-2 rounded inline-block">Cancel</button>
                                         </div>
                                  <?php else: ?>
                                    <p class="mt-1"><?php echo htmlspecialchars($commentData['comment_content']); ?></p>
                                 <?php endif; ?>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No comments found for this article.</p>
                <?php endif; ?>
                  <?php
                    if (isset($_SESSION['error'])) {
                    echo '<div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded-lg mb-6">' . 
                         htmlspecialchars($_SESSION['error']) . 
                         '</div>';
                    unset($_SESSION['error']);
                 } ?>

                <!-- Add Comment Form -->
                 <form class="mt-6" method="post" action="/drive-loc v2/pages/single_post.php?article_id=<?php echo $article_id; ?>">
                    <textarea name="comment_content" placeholder="Add your comment..." class="bg-gray-800 text-white px-4 py-2 rounded w-full"></textarea>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-2">Post Comment</button>
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
       function editComment(commentId) {
            document.getElementById('comment-text-' + commentId).style.display = 'none';
            document.getElementById('comment-edit-' + commentId).style.display = 'block';
          }
          function cancelEdit(commentId) {
               document.getElementById('comment-text-' + commentId).style.display = 'block';
              document.getElementById('comment-edit-' + commentId).style.display = 'none';
          }
    </script>
</body>
</html>