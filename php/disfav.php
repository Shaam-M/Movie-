<?php
session_start();

if (isset($_SESSION['id'])) {
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'auth_db';

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $user_id = $_SESSION['id'];

        $sql = 'SELECT movie_id FROM favorites WHERE user_id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);

        $movieIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode($movieIds);
    } catch (PDOException $e) {
        echo 'Error fetching user-specific movie IDs: ' . $e->getMessage();
    }
} else {
    echo 'User not logged in';
}
?>