<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post - Drive & Loc</title>
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
                    <a href="/" class="text-2xl font-bold tracking-wider">Drive & Loc</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="py-32 mt-16 mx-8 md:mx-16">
        <div class="max-w-xl mx-auto">
           <?php
            session_start();
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
            <h1 class="text-3xl font-bold mb-6">Add New Blog Post</h1>
            <form action="../controllers/publish_article.php" method="POST" class="space-y-4" enctype="multipart/form-data">
                <div>
                    <label for="title" class="block text-gray-300 mb-1">Title</label>
                    <input type="text" id="title" name="title" class="bg-gray-800 text-white px-4 py-2 rounded w-full" required>
                </div>
                <div>
                    <label for="theme" class="block text-gray-300 mb-1">Theme</label>
                    <select id="theme" name="theme" class="bg-gray-800 text-white px-4 py-2 rounded w-full">
                        <option value="">Select a Theme</option>
                        <option value="1">Theme 1</option>
                        <option value="2">Theme 2</option>
                    </select>
                </div>
                <div>
                    <label for="content" class="block text-gray-300 mb-1">Content</label>
                    <textarea id="content" name="content" rows="8" class="bg-gray-800 text-white px-4 py-2 rounded w-full" required>
                    </textarea>
                </div>
                <div>
                    <label for="tags" class="block text-gray-300 mb-1">Tags (comma separated)</label>
                    <input type="text" id="tags" name="tags" class="bg-gray-800 text-white px-4 py-2 rounded w-full">
                </div>
                <div>
                    <label for="images" class="block text-gray-300 mb-1">Images (optional)</label>
                    <input type="file" id="images" name="images[]" multiple class="bg-gray-800 text-white rounded w-full">
                </div>
                <div>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Publish Post</button>
                </div>
            </form>
        </div>
    </section>

    <footer class="bg-gray-900 py-12">
        <div class="mx-8 md:mx-16 text-center text-gray-400">
            © <?php echo date("Y"); ?> Drive & Loc. All rights reserved.
        </div>
    </footer>
</body>
</html>