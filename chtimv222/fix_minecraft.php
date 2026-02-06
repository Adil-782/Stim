<?php
include 'includes/db_connect.php';
echo "FIXING MINECRAFT...<br>";

// URL alternative (Wikipedia)
$url = "https://upload.wikimedia.org/wikipedia/en/5/51/Minecraft_cover.png";
$safe_url = mysqli_real_escape_string($conn, $url);

$sql = "UPDATE games SET image_cover = '$safe_url' WHERE title = 'Minecraft'";
if (mysqli_query($conn, $sql)) {
    echo "Minecraft Updated. Rows: " . mysqli_affected_rows($conn);
}
else {
    echo "Error: " . mysqli_error($conn);
}
?>
