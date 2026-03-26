<?php

$db_host = $_ENV['MYSQLHOST'] ?? $_SERVER['MYSQLHOST'] ?? getenv('MYSQLHOST') ?: 'localhost';
$db_user = $_ENV['MYSQLUSER'] ?? $_SERVER['MYSQLUSER'] ?? getenv('MYSQLUSER') ?: 'root';
$db_pass = $_ENV['MYSQLPASSWORD'] ?? $_SERVER['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD') ?: '';
$db_name = $_ENV['MYSQLDATABASE'] ?? $_SERVER['MYSQLDATABASE'] ?? getenv('MYSQLDATABASE') ?: 'course_calendar';
$db_port = $_ENV['MYSQLPORT'] ?? $_SERVER['MYSQLPORT'] ?? getenv('MYSQLPORT') ?: 3306;

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, (int) $db_port);
    $conn->set_charset("utf8mb4");

    $sql = "CREATE TABLE IF NOT EXISTS `appointments` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `course_name` varchar(255) NOT NULL,
      `instructor_name` varchar(255) NOT NULL,
      `start_date` date NOT NULL,
      `end_date` date NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `start_time` time NOT NULL,
      `end_time` time NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    if ($conn->query($sql) === TRUE) {
        echo "✅ Table 'appointments' created successfully!<br>";
        echo "⚠️ <strong>Now delete init_db.php from your repo and push again.</strong>";
    } else {
        echo "❌ Error: " . $conn->error;
    }

    $conn->close();
} catch (Exception $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
