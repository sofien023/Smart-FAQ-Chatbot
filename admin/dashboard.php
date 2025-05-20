<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get FAQ statistics
$stmt = $pdo->query("SELECT COUNT(*) as total_faqs FROM faqs");
$faq_count = $stmt->fetch()['total_faqs'];

$stmt = $pdo->query("SELECT COUNT(*) as total_logs FROM chat_logs");
$chat_logs_count = $stmt->fetch()['total_logs'];

// Get recent chat logs
$stmt = $pdo->query("
    SELECT question, answer, created_at, is_ai_response 
    FROM chat_logs 
    ORDER BY created_at DESC 
    LIMIT 10
");
$recent_logs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - University FAQ Chatbot</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <h1>Admin Dashboard</h1>
            <ul>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="faqs.php">Manage FAQs</a></li>
                <li><a href="logs.php">View Logs</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-content">
            <div class="stats-container">
                <div class="stat-box">
                    <h3>Total FAQs</h3>
                    <p><?php echo $faq_count; ?></p>
                </div>
                <div class="stat-box">
                    <h3>Total Chat Logs</h3>
                    <p><?php echo $chat_logs_count; ?></p>
                </div>
            </div>

            <div class="recent-logs">
                <h2>Recent Chat Logs</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_logs as $log): ?>
                        <tr>
                            <td><?php echo date('Y-m-d H:i', strtotime($log['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($log['question']); ?></td>
                            <td><?php echo htmlspecialchars($log['answer']); ?></td>
                            <td><?php echo $log['is_ai_response'] ? 'AI' : 'FAQ'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html> 