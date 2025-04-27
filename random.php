<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
  header("Location: login.html");
  exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movies_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX request
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $sql = "SELECT m.*, GROUP_CONCAT(mg.genre SEPARATOR ', ') AS GENRES
            FROM MOVIES m
            LEFT JOIN movie_genres mg ON m.id = mg.movie_id
            GROUP BY m.id
            ORDER BY RAND() 
            LIMIT 1";
    $result = $conn->query($sql);

    if (!$result) {
        error_log("SQL Error: " . $conn->error);
        echo json_encode(['error' => 'Database query failed']);
        exit();
    }

    $movie = $result->fetch_assoc();

    // Check if movie is in the user's watchlist
    $check_watchlist = $conn->prepare("SELECT * FROM WATCHLIST WHERE user_id = ? AND movie_id = ?");
    $check_watchlist->bind_param("ii", $_SESSION['id'], $movie['ID']);
    $check_watchlist->execute();
    $in_watchlist = $check_watchlist->get_result()->num_rows > 0;
    $check_watchlist->close();

    // Return movie data as JSON
    echo json_encode([
        'movie' => $movie,
        'in_watchlist' => $in_watchlist
    ]);
    exit();
}

// Get random movie from database with concatenated genres
$sql = "SELECT m.*, GROUP_CONCAT(mg.genre SEPARATOR ', ') AS GENRES
    FROM MOVIES m
    LEFT JOIN movie_genres mg ON m.id = mg.movie_id
    GROUP BY m.id
    ORDER BY RAND() 
    LIMIT 1";
$result = $conn->query($sql);
$movie = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Surprise Me!</title>
  <link rel="icon" type="image/x-icon" href="images/surpriseme.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/script.js"></script>
  </head>

  <body>
  <nav class="nav-bar">
    <ul>
    <li><a href="dashboard.php">Home</a></li>
    <li><a href="profile.php">Profile</a></li>
    <li><a href="about.html">About Us</a></li>
    <li>
      <div class="dropdown">
      <button
        class="btn btn-secondary dropdown-toggle"
        type="button"
        id="dropdownMenuButtonDark"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        style="border-radius: 20px"
      >
        Menu
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButtonDark">
        <li><a class="dropdown-item" href="dashboard.php">Movies</a></li>
        <li><a class="dropdown-item" href="watchlist.php">Watchlist</a></li>
        <li><a class="dropdown-item" href="trending.php">Trending</a></li>
        <li><a class="dropdown-item" href="toprated.php">Top Rated</a></li>
        <li><a class="dropdown-item" href="test.html">Test Me</a></li>
      </ul>
      </div>
    </li>
    </ul>
  </nav>

  <header class="header">
    <h1><b>SURPRISE ME</b></h1>
  </header>

  <main class="main-content">
    <div class="movie-card" style="max-width: 600px; margin: 0 auto;">
    <img src="<?php echo htmlspecialchars($movie['POSTERURL']); ?>" alt="<?php echo htmlspecialchars($movie['TITLE']); ?>" style="max-width: 300px;">
    <h3><?php echo htmlspecialchars($movie['TITLE']); ?></h3>
    <p><strong>Year:</strong> <?php echo htmlspecialchars($movie['RELEASE_YEAR']); ?></p>
    <p><strong>Director:</strong> <?php echo htmlspecialchars($movie['DIRECTOR']); ?></p>
    <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['GENRES']); ?></p>
    <p><?php echo htmlspecialchars($movie['DESCRIPTION']); ?></p>
    <div class="button-group">
      <a href="#" class="btn btn-primary">Get Another Movie</a>
      <?php
        // Check if movie is already in watchlist
        $check_watchlist = $conn->prepare("SELECT * FROM WATCHLIST WHERE user_id = ? AND movie_id = ?");
        $check_watchlist->bind_param("ii", $_SESSION['id'], $movie['ID']);
        $check_watchlist->execute();
        $in_watchlist = $check_watchlist->get_result()->num_rows > 0;
        $check_watchlist->close();
        ?>
        <?php if(!$in_watchlist): ?>
          <a href="#" 
            class="btn btn-sm btn-primary add-to-watchlist" 
            id="add-<?php echo $movie['ID']; ?>" 
            onclick="event.stopPropagation();">
            Add to Watchlist
          </a>
        <?php else: ?>
          <a href="#" 
            class="btn btn-danger remove-from-watchlist" 
            id="remove-<?php echo $movie['ID']; ?>" 
            onclick="event.stopPropagation();">
            Remove from Watchlist
          </a>
        <?php endif; ?>
    </div>
    </div>
  </main>

  <div class="copyright-wrapper">
      &copy; 2025 Reel Good Pick &#124; All rights reserved
    </div>
  </body>
</html>
<?php
$conn->close(); 
?>