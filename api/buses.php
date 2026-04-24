<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

try {
    if ($method === 'GET') {
        $busStmt = $pdo->query(
            'SELECT
                buses.id,
                buses.route_id,
                buses.plate,
                buses.runtime,
                routes.name AS route_name
             FROM buses
             JOIN routes ON buses.route_id = routes.id
             ORDER BY buses.id ASC'
        );

        $routeStmt = $pdo->query('SELECT id, name FROM routes ORDER BY id ASC');

        echo json_encode([
            'buses' => $busStmt->fetchAll(),
            'routes' => $routeStmt->fetchAll()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method === 'POST') {
        $stmt = $pdo->prepare('INSERT INTO buses (route_id, plate, runtime) VALUES (?, ?, ?)');
        $stmt->execute([
            $input['route_id'],
            $input['plate'],
            $input['runtime']
        ]);

        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method === 'PUT') {
        $stmt = $pdo->prepare('UPDATE buses SET runtime = ? WHERE id = ?');
        $stmt->execute([
            $input['runtime'],
            $input['id']
        ]);

        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method === 'DELETE') {
        $stmt = $pdo->prepare('DELETE FROM buses WHERE id = ?');
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
