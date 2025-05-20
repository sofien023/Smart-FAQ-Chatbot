<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle FAQ deletion
if (isset($_POST['delete_faq'])) {
    $faq_id = $_POST['faq_id'];
    $stmt = $pdo->prepare("DELETE FROM faqs WHERE id = ?");
    $stmt->execute([$faq_id]);
    header('Location: faqs.php');
    exit;
}

// Handle FAQ addition/editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $category = $_POST['category'];
    
    if (isset($_POST['faq_id'])) {
        // Update existing FAQ
        $stmt = $pdo->prepare("
            UPDATE faqs 
            SET question = ?, answer = ?, category = ? 
            WHERE id = ?
        ");
        $stmt->execute([$question, $answer, $category, $_POST['faq_id']]);
    } else {
        // Add new FAQ
        $stmt = $pdo->prepare("
            INSERT INTO faqs (question, answer, category, created_by) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$question, $answer, $category, $_SESSION['admin_id']]);
    }
    header('Location: faqs.php');
    exit;
}

// Get all FAQs
$stmt = $pdo->query("
    SELECT f.*, u.username as created_by_username 
    FROM faqs f 
    LEFT JOIN users u ON f.created_by = u.id 
    ORDER BY f.created_at DESC
");
$faqs = $stmt->fetchAll();

// Get all categories
$stmt = $pdo->query("SELECT name FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage FAQs - University FAQ Chatbot</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <h1>Admin Dashboard</h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="faqs.php" class="active">Manage FAQs</a></li>
                <li><a href="logs.php">View Logs</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-content">
            <h2>Manage FAQs</h2>
            
            <div class="faq-form">
                <h3>Add New FAQ</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select name="category" id="category" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>">
                                    <?php echo htmlspecialchars($category); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="question">Question</label>
                        <input type="text" id="question" name="question" required>
                    </div>
                    <div class="form-group">
                        <label for="answer">Answer</label>
                        <textarea id="answer" name="answer" rows="4" required></textarea>
                    </div>
                    <button type="submit">Add FAQ</button>
                </form>
            </div>

            <div class="faq-list">
                <h3>Existing FAQs</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($faqs as $faq): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($faq['category']); ?></td>
                            <td><?php echo htmlspecialchars($faq['question']); ?></td>
                            <td><?php echo htmlspecialchars($faq['answer']); ?></td>
                            <td><?php echo htmlspecialchars($faq['created_by_username']); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($faq['created_at'])); ?></td>
                            <td>
                                <form method="POST" action="" style="display: inline;">
                                    <input type="hidden" name="faq_id" value="<?php echo $faq['id']; ?>">
                                    <button type="submit" name="delete_faq" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html> 