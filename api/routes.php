<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

try {
    if ($method === 'GET' && isset($_GET['id'])) {
        $routeStmt = $pdo->prepare('SELECT * FROM routes WHERE id = ?');
        $routeStmt->execute([$_GET['id']]);
        $route = $routeStmt->fetch();

        $stationStmt = $pdo->prepare(
            'SELECT
                route_stations.station_id,
                stations.name AS station_name,
                route_stations.sort_order,
                route_stations.drive_time,
                route_stations.stop_time
             FROM route_stations
             JOIN stations ON route_stations.station_id = stations.id
             WHERE route_stations.route_id = ?
             ORDER BY route_stations.sort_order ASC'
        );
        $stationStmt->execute([$_GET['id']]);

        echo json_encode([
            'route' => $route,
            'routeStations' => $stationStmt->fetchAll()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method === 'GET') {
        $routeStmt = $pdo->query(
            'SELECT
                routes.id,
                routes.name,
                routes.per_row,
                COUNT(route_stations.id) AS station_count
             FROM routes
             LEFT JOIN route_stations ON routes.id = route_stations.route_id
             GROUP BY routes.id, routes.name, routes.per_row
             ORDER BY routes.id ASC'
        );

        $stationStmt = $pdo->query('SELECT id, name FROM stations ORDER BY id ASC');

        echo json_encode([
            'routes' => $routeStmt->fetchAll(),
            'stations' => $stationStmt->fetchAll()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method === 'POST') {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('INSERT INTO routes (name, per_row) VALUES (?, ?)');
        $stmt->execute([
            $input['name'],
            $input['per_row']
        ]);

        $routeId = $pdo->lastInsertId();
        saveRouteStations($pdo, $routeId, $input['stations']);

        $pdo->commit();
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method === 'PUT') {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('UPDATE routes SET name = ?, per_row = ? WHERE id = ?');
        $stmt->execute([
            $input['name'],
            $input['per_row'],
            $input['id']
        ]);

        $stmt = $pdo->prepare('DELETE FROM route_stations WHERE route_id = ?');
        $stmt->execute([$input['id']]);
        saveRouteStations($pdo, $input['id'], $input['stations']);

        $pdo->commit();
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method === 'DELETE') {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('DELETE FROM route_stations WHERE route_id = ?');
        $stmt->execute([$input['id']]);

        $stmt = $pdo->prepare('DELETE FROM buses WHERE route_id = ?');
        $stmt->execute([$input['id']]);

        $stmt = $pdo->prepare('DELETE FROM routes WHERE id = ?');
        $stmt->execute([$input['id']]);

        $pdo->commit();
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => '不支援的請求方法'], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

function saveRouteStations(PDO $pdo, int|string $routeId, array $stations): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO route_stations
        (route_id, station_id, sort_order, drive_time, stop_time)
        VALUES (?, ?, ?, ?, ?)'
    );

    foreach ($stations as $station) {
        $stmt->execute([
            $routeId,
            $station['station_id'],
            $station['sort_order'],
            $station['drive_time'],
            $station['stop_time']
        ]);
    }
}
