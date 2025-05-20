<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$question = $data['question'] ?? '';

if (empty($question)) {
    echo json_encode(['success' => false, 'error' => 'No question provided']);
    exit;
}

try {
    // Search for matching FAQ
    $stmt = $pdo->prepare("
        SELECT answer 
        FROM faqs 
        WHERE MATCH(question) AGAINST(:question IN NATURAL LANGUAGE MODE)
        LIMIT 1
    ");
    $stmt->execute(['question' => $question]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $answer = $result['answer'];
        $is_ai_response = false;
    } else {
        // If no FAQ match, use OpenAI API (if configured)
        $answer = "I'm sorry, I don't have an answer for that question yet. Please contact the university administration for more information.";
        $is_ai_response = false;
    }

    // Log the chat interaction
    $stmt = $pdo->prepare("
        INSERT INTO chat_logs (user_session_id, question, answer, is_ai_response)
        VALUES (:session_id, :question, :answer, :is_ai_response)
    ");
    $stmt->execute([
        'session_id' => $_SESSION['chat_session_id'],
        'question' => $question,
        'answer' => $answer,
        'is_ai_response' => $is_ai_response
    ]);

    echo json_encode([
        'success' => true,
        'answer' => $answer
    ]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred while processing your request'
    ]);
}
?> 