<?php
header('Content-Type: application/json; charset=utf-8');

$allStations = [
    '台北車站',
    '西門',
    '龍山寺',
    '江子翠',
    '新埔',
    '板橋',
    '府中',
    '亞東醫院',
    '海山',
    '土城',
    '永寧'
];

$count = rand(5, count($allStations));
$names = array_slice($allStations, 0, $count);
$busIndex = rand(0, $count - 1);
$plate = 'C' . rand(10000, 99999);
$perRow = rand(2, 4);

$stations = [];

foreach ($names as $index => $name) {
    if ($index < $busIndex) {
        $status = 'arrived';
        $minutes = 0;
    } elseif ($index === $busIndex) {
        $status = 'running';
        $minutes = rand(1, 5);
    } else {
        $status = 'not_started';
        $minutes = null;
    }

    $stations[] = [
        'id' => $index + 1,
        'name' => $name,
        'status' => $status,
        'plate' => $status === 'not_started' ? '' : $plate,
        'minutes' => $minutes
    ];
}

echo json_encode([
    'routeName' => '板南線',
    'perRow' => $perRow,
    'stations' => $stations
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
