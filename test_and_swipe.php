<?php
// Start the session to manage user data across requests
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movies_db";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the database connection was successful
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Handle AJAX requests
$request = json_decode(file_get_contents('php://input'), true); // Decode the incoming JSON request

if (isset($request['action'])) {
    // If the action is to fetch movies based on the test results
    if ($request['action'] === 'getMovies') {
        // Extract user input for the movie preferences from the request
        $mood = $request['mood'] ?? null;
        $length = $request['length'] ?? null;
        $era = $request['era'] ?? null;
        $setting = $request['setting'] ?? null;

        // Start building the SQL query to get movies based on the user's preferences
        $query = "SELECT * 
                  FROM MOVIES m 
                  LEFT JOIN MOVIE_GENRES mg ON m.id = mg.movie_id 
                  WHERE 1=1"; // Start with a base query that fetches all movies

        $params = []; // Array to hold query parameters for binding
        $types = ""; // String to hold the types of the parameters for binding

        // If a mood (genre) is selected, add a condition to filter by genre
        if ($mood) {
            $query .= " AND EXISTS (
                SELECT 1 FROM MOVIE_GENRES mg2 
                WHERE mg2.movie_id = m.id 
                AND LOWER(mg2.genre) = ? 
            )";
            $params[] = strtolower($mood); // Append the genre parameter to the params array
            $types .= "s"; // Indicate that the parameter is a string
        }

        // If a movie length is selected, filter by duration
        if ($length) {
            switch ($length) {
                case 'short':
                    $query .= " AND m.duration < 90"; // Movies shorter than 90 minutes
                    break;
                case 'medium':
                    $query .= " AND m.duration >= 90 AND m.duration <= 120"; // Movies between 90-120 minutes
                    break;
                case 'long':
                    $query .= " AND m.duration > 120"; // Movies longer than 120 minutes
                    break;
            }
        }

        // If an era is selected, filter by release year
        if ($era) {
            if ($era == 3000) { // Present era (2010-present)
                $query .= " AND m.release_year >= 2010";
            } elseif ($era == 2010) { // Modern era (1980-2010)
                $query .= " AND m.release_year >= 1980 AND m.release_year < 2010";
            } elseif ($era == 1980) { // Classic era (before 1980)
                $query .= " AND m.release_year < 1980";
            }
        }

        // If a setting (genre) is selected, filter by movie setting (realistic, fantasy, etc.)
        if ($setting) {
            $query .= " AND EXISTS (
                SELECT 1 FROM MOVIE_GENRES mg3 
                WHERE mg3.movie_id = m.id 
                AND LOWER(mg3.genre) = ?
            )";
            $params[] = strtolower($setting); // Append the genre parameter for the setting
            $types .= "s"; // Indicate that the parameter is a string
        }

        // Add random ordering to the results
        $query .= " GROUP BY m.id ORDER BY RAND()";

        // Debugging: Log the query and parameters to check if the SQL is correct
        error_log("Query: $query");
        error_log("Params: " . json_encode($params));

        // Prepare and execute the SQL statement
        $stmt = $conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params); // Bind parameters to the SQL query
        }
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Get the result set
        $movies = $result->fetch_all(MYSQLI_ASSOC); // Fetch all movies as an associative array

        // Return the movie data as a JSON response
        echo json_encode(['success' => true, 'movies' => $movies]);
    }
    // If the action is to add a movie to the user's watchlist
    elseif ($request['action'] === 'addToWatchlist') {
        // Get the movie ID and user ID from the request
        $movie_id = $request['movie_id'];
        $user_id = $_SESSION['id'] ?? null; // Check if the user is logged in (session ID)

        // If the user is not logged in, return an error response
        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            exit; // Exit early if the user is not logged in
        }

        // Prepare the SQL query to insert the movie into the watchlist
        $stmt = $conn->prepare("INSERT INTO WATCHLIST (user_id, movie_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $movie_id); // Bind user ID and movie ID as integers

        // Execute the insert query and return a success or failure response
        if ($stmt->execute()) {
            echo json_encode(['success' => true]); // Movie successfully added to the watchlist
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add movie to watchlist']);
        }
    }
}

// Close the database connection
$conn->close();
?>