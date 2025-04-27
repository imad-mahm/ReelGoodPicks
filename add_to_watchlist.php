<?php
// Start the session to manage user data across requests
session_start();

// Check if the user is logged in
// If not, redirect them to the login page
if (!isset($_SESSION['id'])) {
    header('Location: login.html'); // Redirect to login page if not logged in
    exit(); // Stop further script execution after redirect
}

// Get the movie ID from the POST request and sanitize it to prevent XSS attacks
$movie_id = htmlspecialchars($_POST['movie_id']);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movies_db";

// Create a connection to the database using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Retrieve the user ID from the session to associate with the watchlist entry
$user_id = $_SESSION['id'];

// Prepare the SQL query to insert the movie into the watchlist for the logged-in user
$add_to_watchlist = $conn->prepare("INSERT INTO WATCHLIST (user_id, movie_id) VALUES (?, ?)");

// Bind the user ID and movie ID as parameters to the query (both integers)
$add_to_watchlist->bind_param("ii", $user_id, $movie_id);

// Execute the query to insert the movie into the watchlist
$add_to_watchlist->execute();

// Close the prepared statement to free up resources
$add_to_watchlist->close();

// Close the database connection after completing the operation
$conn->close();

// Get the referring page URL to redirect the user back to the previous page after adding the movie
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'genre.php';

// Redirect the user back to the referring page or default to 'genre.php' if no referrer is found
header("Location: " . $referer);

// Exit to ensure no further code is executed after the redirect
exit();
?>