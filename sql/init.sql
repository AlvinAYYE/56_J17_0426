CREATE DATABASE IF NOT EXISTS ptqs
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ptqs;

DROP TABLE IF EXISTS buses;
DROP TABLE IF EXISTS route_stations;
DROP TABLE IF EXISTS routes;
DROP TABLE IF EXISTS stations;

CREATE TABLE stations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL
);

CREATE TABLE routes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  per_row INT NOT NULL DEFAULT 3
);

CREATE TABLE route_stations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  route_id INT NOT NULL,
  station_id INT NOT NULL,
  sort_order INT NOT NULL,
  drive_time INT NOT NULL DEFAULT 1,
  stop_time INT NOT NULL DEFAULT 1
);

CREATE TABLE buses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  route_id INT NOT NULL,
  plate VARCHAR(20) NOT NULL,
  runtime INT NOT NULL DEFAULT 0
);

INSERT INTO stations (name) VALUES
('台北車站'),
('西門'),
('龍山寺'),
('江子翠'),
('新埔'),
('板橋'),
('府中'),
('亞東醫院'),
('海山'),
('土城'),
('永寧');

INSERT INTO routes (name, per_row) VALUES
('板南線練習路線', 3);

INSERT INTO route_stations (route_id, station_id, sort_order, drive_time, stop_time) VALUES
(1, 1, 1, 2, 1),
(1, 2, 2, 2, 1),
(1, 3, 3, 3, 1),
(1, 4, 4, 3, 1),
(1, 5, 5, 2, 1),
(1, 6, 6, 2, 1);

INSERT INTO buses (route_id, plate, runtime) VALUES
(1, 'C12345', 5),
(1, 'C67890', 12);
