<?php
session_start();

if (isset($_SESSION['id']) && isset($_POST['movie_id'])) {
    // Replace with your database connection details
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'auth_db';

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $user_id = $_SESSION['id'];
        $movie_id = $_POST['movie_id'];

        $sql = 'DELETE FROM favorites WHERE user_id = ? AND movie_id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $movie_id]);

        echo 'Movie removed successfully';
    } catch (PDOException $e) {
        echo 'Error removing movie: ' . $e->getMessage();
    }
} else {
    echo 'User not logged in or movie ID not provided';
}
?>