<?php
require_once 'connection.php';
Database::setUpConnection();

$query = "SELECT * FROM events";
$result = Database::search($query);

header("Content-type: text/xml");

echo "<?xml version='1.0' encoding='UTF-8'?>\n";
echo "<campus_events>\n";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "\t<event>\n";
        echo "\t\t<id>" . $row['event_id'] . "</id>\n";
        echo "\t\t<title>" . htmlspecialchars($row['title']) . "</title>\n"; 
        echo "\t\t<date>" . $row['event_date'] . "</date>\n";
        echo "\t\t<status>" . $row['status'] . "</status>\n";
        echo "\t</event>\n";
    }
}

echo "</campus_events>";
?>