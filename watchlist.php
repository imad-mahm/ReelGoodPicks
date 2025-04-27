<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movies_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user's watchlist
$user_id = $_SESSION['id'];
$query = "SELECT m.* FROM MOVIES m 
          INNER JOIN WATCHLIST w ON m.id = w.movie_id 
          WHERE w.user_id = ?
          ORDER BY w.added_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$watchlist_movies = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Watchlist - Reel Good Picks</title>
    <link rel="icon" type="image/x-icon" href="images/watchlist.webp" />
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/style.css" />
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
              <li>
                <a class="dropdown-item" href="dashboard.php">Movies</a>
              </li>
              <li>
                <a class="dropdown-item" href="random.php">Surprise me</a>
              </li>
              <li>
                <a class="dropdown-item" href="trending.php">Trending</a>
              </li>
              <li>
                <a class="dropdown-item" href="toprated.php">Top Rated</a>
              </li>
              <li><a class="dropdown-item" href="test.html">Test Me</a></li>
            </ul>
          </div>
        </li>
      </ul>
    </nav>
    <header class="header">
      <h1><b>MY WATCHLIST</b></h1>
    </header>

    <main class="main-content">
      <div class="movie-grid">
        <?php if (count($watchlist_movies) > 0): ?>
          <?php foreach ($watchlist_movies as $movie): ?>
            <div class="movie-card">
              <img src="<?php echo htmlspecialchars($movie['POSTERURL']); ?>" alt="<?php echo htmlspecialchars($movie['TITLE']); ?>">
              <h3><?php echo htmlspecialchars($movie['TITLE']); ?></h3>
              <p class="movie-description"><?php echo htmlspecialchars($movie['DESCRIPTION']); ?></p>
              <a href="#" 
                class="btn btn-danger remove-from-watchlist" 
                id="remove-<?php echo $movie['ID']; ?>" 
                onclick="event.stopPropagation();">
                Remove from Watchlist
              </a>
              <?php if(!empty($movie['TRAILERURL'])): ?>
                <a href="<?php echo htmlspecialchars($movie['TRAILERURL']); ?>" target="_blank" class="btn btn-danger mt-2" onclick="event.stopPropagation();">
                  Watch Trailer
                </a>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="no-movies">Your watchlist is empty. Add some movies to get started!</p>
        <?php endif; ?>
      </div>
    </main>
    <div class="copyright-wrapper">
      &copy; 2025 Reel Good Pick &#124; All rights reserved
    </div>
    <script src="js/script.js"></script>
  </body>
</html>
<?php
$stmt->close();
$conn->close();
?>
