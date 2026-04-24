<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

try {
    if ($method === 'GET') {
        $stmt = $pdo->query('SELECT * FROM stations ORDER BY id ASC');
        echo json_encode([
            'stations' => $stmt->fetchAll()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method === 'POST') {
        $stmt = $pdo->prepare('INSERT INTO stations (name) VALUES (?)');
        $stmt->execute([$input['name']]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method === 'PUT') {
        $stmt = $pdo->prepare('UPDATE stations SET name = ? WHERE id = ?');
        $stmt->execute([$input['name'], $input['id']]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method === 'DELETE') {
        $stmt = $pdo->prepare('DELETE FROM stations WHERE id = ?');
        $stmt->execute([$input['id']]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => '不支援的請求方法'], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
