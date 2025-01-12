<?php

require_once '../classes/database.php';
require_once '../classes/theme.php';
require_once '../classes/tag.php';
require_once '../classes/article.php';
require_once '../classes/comment.php';
require_once '../classes/user.php';
require_once '../classes/role.php';

session_start();

if (!isset($_SESSION['user_id'])) {
       $_SESSION['error'] = "You must be logged in to access the admin dashboard.";
        header('Location: ../pages/login.php'); // Corrected redirect here
        exit();
    }
    
$user = new User();
$userRole = $user->getUserById($_SESSION['user_id']);
if($userRole && $userRole['role_id'] !== 1){
       $_SESSION['error'] = "You must be an admin to access the admin dashboard.";
        header('Location: ../pages/user_page.php');
        exit();
}

$themeObj = new Theme();
$tagObj = new Tag();
$articleObj = new Article();
$commentObj = new Comment();

$themes = $themeObj->viewThemes();
$tags = $tagObj->viewTags();
$articles = $articleObj->viewArticles();
$comments = $commentObj->ViewComments();



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Drive & Loc</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Space Grotesk', sans-serif;
        }

        .clip-path-diagonal {
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .car-gradient {
            background: linear-gradient(90deg, #FF0000 0%, #000000 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .car-gradient-modified {
            background: linear-gradient(90deg, #FF0000 0%, #FF0000 30%, #000000 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .diagonal-border {
            background: linear-gradient(135deg, #FF0000 0%, #000000 100%);
            transform: skew(-12deg);
        }

        .hover-grow {
            transition: transform 0.3s ease;
        }

        .hover-grow:hover {
            transform: scale(1.02) translateY(-5px);
        }

        /* Admin Specific Styles */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #111;
            padding-top: 20px;
            z-index: 60;
        }

        .sidebar-item {
            padding: 15px 20px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .sidebar-item:hover {
            background-color: #222;
        }

        .sidebar-item.active {
            background-color: #333;
            border-left: 4px solid #FF0000;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px); /* Adjusted width */
        }

        .table-container {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th, .data-table td {
            border-bottom: 1px solid #333;
            padding: 12px 15px;
            text-align: left;
        }

        .data-table th {
            font-weight: bold;
            text-transform: uppercase;
            color: #ddd;
        }

        .action-buttons button {
            background-color: transparent;
            border: 1px solid #FF0000;
            color: #FF0000;
            padding: 8px 12px;
            margin-right: 5px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .action-buttons button:hover {
            background-color: #FF0000;
            color: #000;
        }

        .action-buttons .edit-button {
            border-color: #FFD700; /* Yellow Gold */
            color: #FFD700;
        }

        .action-buttons .edit-button:hover {
            background-color: #FFD700;
            color: #000;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #ddd;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
        }

        .submit-button {
            background-color: #FF0000;
            color: #000;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: opacity 0.2s ease;
        }

        .submit-button:hover {
            opacity: 0.8;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #222;
            margin: 15% auto;
            padding: 30px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            position: relative;
        }

        .close-button {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .statistics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .statistic-card {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .statistic-value {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .statistic-label {
            text-transform: uppercase;
            font-size: 0.8em;
            color: #aaa;
        }

        /* Improved Styling for Multiple Forms in Modals */
        .form-container {
            border: 1px solid #555;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            position: relative;
        }

        .form-container:last-child {
            margin-bottom: 0;
        }

        .remove-form-button {
            position: absolute;
            top: 5px;
            right: 10px;
            color: #aaa;
            font-size: 1.2em;
            cursor: pointer;
            background: none;
            border: none;
        }

        .remove-form-button:hover {
            color: white;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

    </style>
</head>
<body class="bg-black text-white overflow-x-hidden">
    <!-- Admin Dashboard Layout -->
    <div class="flex">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-3 h-12 bg-red-600"></div>
                    <span class="text-2xl font-bold tracking-wider">Drive & Loc Blog Admin</span>
                </div>
            </div>
            <nav>
                <ul>
                    <li class="sidebar-item active" data-target="dashboard-content">
                        <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                    </li>
                    <li class="sidebar-item" data-target="articles-content">
                        <i class="fas fa-newspaper mr-3"></i> Articles
                    </li>
                    <li class="sidebar-item" data-target="themes-content">
                        <i class="fas fa-palette mr-3"></i> Themes
                    </li>
                    <li class="sidebar-item" data-target="tags-content">
                        <i class="fas fa-tags mr-3"></i> Tags
                    </li>
                    <li class="sidebar-item" data-target="comments-content">
                        <i class="fas fa-comment mr-3"></i> Comments
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="content">

            <!-- Dashboard Content -->
            <section id="dashboard-content">
                <h2 class="text-3xl font-bold mb-6">Admin Dashboard</h2>
                <div class="statistics-grid">
                    <div class="statistic-card">
                        <div class="statistic-value car-gradient"><?php echo count($articles); ?></div>
                        <div class="statistic-label">Total Articles</div>
                    </div>
                    <div class="statistic-card">
                        <div class="statistic-value car-gradient"><?php echo count($themes);?></div>
                        <div class="statistic-label">Themes</div>
                    </div>
                    <div class="statistic-card">
                        <div class="statistic-value car-gradient"><?php echo count($tags);?></div>
                        <div class="statistic-label">Tags</div>
                    </div>
                    <div class="statistic-card">
                        <div class="statistic-value car-gradient"><?php echo count($comments);?></div>
                        <div class="statistic-label">Total Comments</div>
                    </div>
                </div>
            </section>

            <!-- Articles Management -->
            <section id="articles-content" class="hidden">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold">Manage Articles</h2>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="text-left p-3 text-gold">Title</th>
                                <th class="text-left p-3 text-gold">Author</th>
                                <th class="text-left p-3 text-gold">Status</th>
                                <th class="text-left p-3 text-gold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
			<?php foreach($articles as $article): ?>
                            <tr class="border-b border-white/10" id="article-row-<?= $article['article_id']; ?>">
                                <td class="p-3"><span class="article-data"><?=htmlspecialchars($article['article_title']); ?></span></td>
                                <td class="p-3"><span class="article-data"><?php 
                                 $userObj = new User();
                                    echo $userObj->getAuthor($article['user_id']); ?></span></td>
				                <td class="p-3"><span class="article-data"><?=htmlspecialchars($article['status']);?></span></td>
                                <td class="p-3">
                                    <form action="accept_article.php" method="post" class="inline-block">
                                        <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Accept</button>
                                    </form>
                                    <form action="reject_article.php" method="post" class="inline-block">
                                        <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Reject</button>
                                    </form>
                                    <form action="edit_article.php" method="post">
                                        <input type="hidden" name="article_id" value="<?php echo $article['article_id'];?>">
                                        <button class="inline-block px-3 py-1 bg-yellow-500 text-black rounded mr-2 hover:bg-yellow-600" type="submit">
                                            Edit
                                        </button>
                                    </form>
                                    <form action="delete_article.php" method="post">
                                        <input type="hidden" name="article_id" value="<?php echo $article['article_id'];?>">
                                        <button class="inline-block px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" type="submit">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Themes Management -->
<section id="themes-content" class="hidden">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold">Manage Themes</h2>
        <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" id="add-theme-button">
            Add Theme
        </button>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th> <!-- Add a new column for actions -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($themes as $theme): ?>
                <tr>
                    <td><?php echo htmlspecialchars($theme['theme_id']); ?></td>
                    <td><?php echo htmlspecialchars($theme['theme_name']); ?></td>
                    <td class="action-buttons">
    <form action="../controllers/delete_theme.php" method="post" class="inline-block">
        <input type="hidden" name="theme_id" value="<?php echo $theme['theme_id']; ?>">
        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
    </form>
    <button type="button" class="bg-yellow-500 hover:bg-yellow-700 text-black font-bold py-2 px-4 rounded edit-theme-button"
            data-theme-id="<?php echo $theme['theme_id']; ?>"
            data-theme-name="<?php echo htmlspecialchars($theme['theme_name']); ?>">
        Edit
    </button>
</td>
</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="add-theme-modal" class="modal">
        <div class="modal-content">
            <span class="close-button">×</span>
            <h2 class="text-2xl font-bold mb-4">Add New Themes</h2>
            <form id="add-theme-form" action="../controllers/add_theme.php" method="post">
                <div id="theme-forms-container">
                    <div class="form-container">
                        <div class="form-group">
                            <label for="theme_name">Theme Name</label>
                            <input type="text" name="theme_name[]" required>
                        </div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" id="add-more-themes" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add More</button>
                    <button type="submit" class="submit-button">Add Theme(s)</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit Theme Modal -->
<div id="edit-theme-modal" class="modal">
    <div class="modal-content">
        <span class="close-button" id="close-edit-theme-modal">×</span>
        <h2 class="text-2xl font-bold mb-4">Edit Theme</h2>
        <form id="edit-theme-form" action="../controllers/edit_theme.php" method="post">
            <input type="hidden" name="theme_id" id="edit_theme_id">
            <div class="form-group">
                <label for="edit_theme_name">Theme Name</label>
                <input type="text" name="theme_name" id="edit_theme_name" required>
            </div>
            <div class="modal-actions">
                <button type="submit" class="submit-button">Save Changes</button>
            </div>
        </form>
    </div>
</div>
</section>

            <!-- Tags Management -->
            <section id="tags-content" class="hidden">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold">Manage Tags</h2>
                    <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" id="add-tag-button">
                        Add Tag
                    </button>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tags as $tag): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tag['tag_id']); ?></td>
                                <td><?php echo htmlspecialchars($tag['tag_name']); ?></td>
                                <td class="action-buttons">
                                    <form action="../controllers/delete_tag.php" method="post" class="inline-block">
                                        <input type="hidden" name="tag_id" value="<?php echo $tag['tag_id']; ?>">
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                    </form>
                                    <button type="button" class="bg-yellow-500 hover:bg-yellow-700 text-black font-bold py-2 px-4 rounded edit-tag-button"
                                            data-tag-id="<?php echo $tag['tag_id']; ?>"
                                            data-tag-name="<?php echo htmlspecialchars($tag['tag_name']); ?>">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add Tag Modal -->
                <div id="add-tag-modal" class="modal">
                    <div class="modal-content">
                        <span class="close-button">×</span>
                        <h2 class="text-2xl font-bold mb-4">Add New Tags</h2>
                        <form id="add-tag-form" action="../controllers/add_tag.php" method="post">
                            <div id="tag-forms-container">
                                <div class="form-container">
                                    <div class="form-group">
                                        <label for="tag_name">Tag Name</label>
                                        <input type="text" name="tag_name[]" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-actions">
                                <button type="button" id="add-more-tags" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add More</button>
                                <button type="submit" class="submit-button">Add Tag(s)</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Edit Tag Modal -->
                <div id="edit-tag-modal" class="modal">
                    <div class="modal-content">
                        <span class="close-button" id="close-edit-tag-modal">×</span>
                        <h2 class="text-2xl font-bold mb-4">Edit Tag</h2>
                        <form id="edit-tag-form" action="../controllers/edit_tag.php" method="post">
                            <input type="hidden" name="tag_id" id="edit_tag_id">
                            <div class="form-group">
                                <label for="edit_tag_name">Tag Name</label>
                                <input type="text" name="tag_name" id="edit_tag_name" required>
                            </div>
                            <div class="modal-actions">
                                <button type="submit" class="submit-button">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Comments Management -->
            <section id="comments-content" class="hidden">
                <h2 class="text-3xl font-bold mb-6">Manage Comments</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Comment</th>
                                <th>Article</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
			<?php foreach($comments as $comment): ?>
                            <tr>
                                <td>Great article!</td>
                                <td>The Future of AI</td>
                                <td>2024-01-21</td>
                                <td class="action-buttons">
                                    <button class="delete-comment">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>I want to visit Bali now!</td>
                                <td>Exploring Bali</td>
                                <td>2024-01-16</td>
                                <td class="action-buttons">
                                    <button class="delete-comment">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
                document.addEventListener('DOMContentLoaded', () => {
            // Tab switching functionality
            function setupTabs() {
                const sidebarItems = document.querySelectorAll('.sidebar-item');
                const contentSections = document.querySelectorAll('.content > section');

                function showSection(targetId) {
                    contentSections.forEach(section => section.classList.add('hidden'));
                    document.getElementById(targetId).classList.remove('hidden');
                }

                function setActive(selectedItem) {
                    sidebarItems.forEach(item => item.classList.remove('active'));
                    selectedItem.classList.add('active');
                }

                sidebarItems.forEach(item => {
                    item.addEventListener('click', function() {
                        const target = this.getAttribute('data-target');
                        showSection(target);
                        setActive(this);
                    });
                });

                // Show dashboard by default
                showSection('dashboard-content');
                setActive(document.querySelector('.sidebar-item[data-target="dashboard-content"]'));
            }

            // Modal functionality
            function setupModals() {
                setupAddThemeModal();
                setupEditThemeModal();
                setupAddTagModal();
                setupEditTagModal(); // Initialize Edit Tag Modal
            }

            function setupAddThemeModal() {
                const addThemeModal = document.getElementById('add-theme-modal');
                const addThemeButton = document.getElementById('add-theme-button');
                const closeAddThemeModalBtn = addThemeModal.querySelector('.close-button');

                addThemeButton.onclick = () => addThemeModal.style.display = "block";
                closeAddThemeModalBtn.onclick = () => addThemeModal.style.display = "none";
                window.onclick = (e) => {
                    if (e.target == addThemeModal) {
                        addThemeModal.style.display = "none";
                    }
                };

                // Dynamic form handling for themes
                document.getElementById('add-more-themes').addEventListener('click', function() {
                    const container = document.getElementById('theme-forms-container');
                    const newForm = container.firstElementChild.cloneNode(true);
                    newForm.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.classList.add('remove-form-button');
                    removeButton.innerHTML = '×';
                    removeButton.addEventListener('click', function() {
                        container.removeChild(newForm);
                    });
                    newForm.appendChild(removeButton);
                    container.appendChild(newForm);
                });
            }

            function setupEditThemeModal() {
                const editThemeModal = document.getElementById('edit-theme-modal');
                const closeEditThemeModalBtn = document.getElementById('close-edit-theme-modal');
                const editThemeButtons = document.querySelectorAll('.edit-theme-button');
                const editThemeIdInput = document.getElementById('edit_theme_id');
                const editThemeNameInput = document.getElementById('edit_theme_name');

                editThemeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const themeId = this.dataset.themeId;
                        const themeName = this.dataset.themeName;

                        editThemeIdInput.value = themeId;
                        editThemeNameInput.value = themeName;
                        editThemeModal.style.display = "block";
                    });
                });

                closeEditThemeModalBtn.onclick = () => editThemeModal.style.display = "none";
                window.onclick = (e) => {
                    if (e.target == editThemeModal) {
                        editThemeModal.style.display = "none";
                    }
                };
            }

            function setupAddTagModal() {
                const addTagModal = document.getElementById('add-tag-modal');
                const addTagButton = document.getElementById('add-tag-button');
                const closeAddTagModalBtn = addTagModal.querySelector('.close-button');

                addTagButton.onclick = () => addTagModal.style.display = "block";
                closeAddTagModalBtn.onclick = () => addTagModal.style.display = "none";
                window.onclick = (e) => {
                    if (e.target == addTagModal) {
                        addTagModal.style.display = "none";
                    }
                };

                // Dynamic form handling for tags
                document.getElementById('add-more-tags').addEventListener('click', function() {
                    const container = document.getElementById('tag-forms-container');
                    const newForm = container.firstElementChild.cloneNode(true);
                    newForm.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.classList.add('remove-form-button');
                    removeButton.innerHTML = '×';
                    removeButton.addEventListener('click', function() {
                        container.removeChild(newForm);
                    });
                    newForm.appendChild(removeButton);
                    container.appendChild(newForm);
                });
            }

            function setupEditTagModal() {
                const editTagModal = document.getElementById('edit-tag-modal');
                const closeEditTagModalBtn = document.getElementById('close-edit-tag-modal');
                const editTagButtons = document.querySelectorAll('.edit-tag-button');
                const editTagIdInput = document.getElementById('edit_tag_id');
                const editTagNameInput = document.getElementById('edit_tag_name');

                editTagButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const tagId = this.dataset.tagId;
                        const tagName = this.dataset.tagName;

                        editTagIdInput.value = tagId;
                        editTagNameInput.value = tagName;
                        editTagModal.style.display = "block";
                    });
                });

                closeEditTagModalBtn.onclick = () => editTagModal.style.display = "none";
                window.onclick = (e) => {
                    if (e.target == editTagModal) {
                        editTagModal.style.display = "none";
                    }
                };
            }

            // Initialize all functionality
            setupTabs();
            setupModals();
            setupEditTagModal();
        });
    </script>

</body>
</html>