<?php

$successMsg = '';
$errorMsg = '';
$eventsFromDB = [];
$conn = null;

// ─── Database Connection ───
$db_host = $_ENV['MYSQLHOST'] ?? $_SERVER['MYSQLHOST'] ?? getenv('MYSQLHOST') ?: 'localhost';
$db_user = $_ENV['MYSQLUSER'] ?? $_SERVER['MYSQLUSER'] ?? getenv('MYSQLUSER') ?: 'root';
$db_pass = $_ENV['MYSQLPASSWORD'] ?? $_SERVER['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD') ?: '';
$db_name = $_ENV['MYSQLDATABASE'] ?? $_SERVER['MYSQLDATABASE'] ?? getenv('MYSQLDATABASE') ?: 'course_calendar';
$db_port = $_ENV['MYSQLPORT'] ?? $_SERVER['MYSQLPORT'] ?? getenv('MYSQLPORT') ?: 3306;

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, (int) $db_port);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    $errorMsg = '❗ Could not connect to database.';
    $conn = null;
}

if ($conn) {

    // ✅ Handle Add
    if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST['action'] ?? '') === "add") {
        $course      = trim($_POST["course_name"] ?? '');
        $instructor  = trim($_POST["instructor_name"] ?? '');
        $start       = $_POST["start_date"] ?? '';
        $end         = $_POST["end_date"] ?? '';
        $startTime   = $_POST["start_time"] ?? '';
        $endTime     = $_POST["end_time"] ?? '';

        if ($course && $instructor && $start && $end && $startTime && $endTime) {
            $stmt = $conn->prepare("INSERT INTO appointments (course_name, instructor_name, start_date, end_date, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $course, $instructor, $start, $end, $startTime, $endTime);
            $stmt->execute();
            $stmt->close();
            header("Location: " . $_SERVER["PHP_SELF"] . "?success=1");
            exit;
        } else {
            header("Location: " . $_SERVER["PHP_SELF"] . "?error=1");
            exit;
        }
    }

    // ✏️ Handle Edit
    if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST['action'] ?? '') === "edit") {
        $id          = $_POST["event_id"] ?? null;
        $course      = trim($_POST["course_name"] ?? '');
        $instructor  = trim($_POST["instructor_name"] ?? '');
        $start       = $_POST["start_date"] ?? '';
        $end         = $_POST["end_date"] ?? '';
        $startTime   = $_POST["start_time"] ?? '';
        $endTime     = $_POST["end_time"] ?? '';

        if ($id && $course && $instructor && $start && $end && $startTime && $endTime) {
            $stmt = $conn->prepare("UPDATE appointments SET course_name=?, instructor_name=?, start_date=?, end_date=?, start_time=?, end_time=? WHERE id=?");
            $stmt->bind_param("ssssssi", $course, $instructor, $start, $end, $startTime, $endTime, $id);
            $stmt->execute();
            $stmt->close();
            header("Location: " . $_SERVER["PHP_SELF"] . "?success=2");
            exit;
        } else {
            header("Location: " . $_SERVER["PHP_SELF"] . "?error=2");
            exit;
        }
    }

    // 🗑️ Handle Delete
    if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST['action'] ?? '') === "delete") {
        $id = $_POST["event_id"] ?? null;
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM appointments WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            header("Location: " . $_SERVER["PHP_SELF"] . "?success=3");
            exit;
        }
    }

    // Messages
    if (isset($_GET["success"])) {
        $successMsg = match ($_GET["success"]) {
            '1' => "Event added successfully",
            '2' => "Event updated successfully",
            '3' => "Event deleted successfully",
            default => ''
        };
    }
    if (isset($_GET["error"])) {
        $errorMsg = 'Something went wrong. Please check your input.';
    }

    // 📅 Fetch Events
    try {
        $result = $conn->query("SELECT * FROM appointments");
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $s = new DateTime($row["start_date"]);
                $e = new DateTime($row["end_date"]);
                $cName = htmlspecialchars($row['course_name'], ENT_QUOTES, 'UTF-8');
                $iName = htmlspecialchars($row['instructor_name'], ENT_QUOTES, 'UTF-8');
                while ($s <= $e) {
                    $eventsFromDB[] = [
                        "id"         => (int) $row["id"],
                        "title"      => "{$cName} - {$iName}",
                        "date"       => $s->format('Y-m-d'),
                        "start"      => $row["start_date"],
                        "end"        => $row["end_date"],
                        "start_time" => $row["start_time"],
                        "end_time"   => $row["end_time"],
                    ];
                    $s->modify('+1 day');
                }
            }
        }
    } catch (Exception $e) {
        $errorMsg = 'Database table not found. Visit /init_db.php to set up.';
    }

    $conn->close();
}
?>
