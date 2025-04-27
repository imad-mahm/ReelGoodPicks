<?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movies_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch movies in descending order of box_office
$sql = "SELECT * FROM movies ORDER BY box_office DESC";
$result = $conn->query($sql);
$movies = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trending Movies</title>
    <link
      rel="icon"
      type="image/x-icon"
      href="images/trend-icon-sign-symbol-design-free-png.webp"
    />
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
                <a class="dropdown-item" href="watchlist.php">Watchlist</a>
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
    <h1><b>Trending</b></h1>
    </header>

    <main class="main-content">
    <div id="movieList" class="movie-grid">
        <?php if (count($movies) > 0): ?>
          <?php foreach ($movies as $movie): ?>
            <div class="movie-card" >
              <img src="<?php echo htmlspecialchars($movie['POSTERURL']); ?>" alt="<?php echo htmlspecialchars($movie['TITLE']); ?>">
              <h3><?php echo htmlspecialchars($movie['TITLE']); ?></h3>
              <p class="movie-description"><?php echo htmlspecialchars($movie['DESCRIPTION']); ?></p>
              <?php if(isset($_SESSION['id'])): ?>
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
              <?php endif; ?>
              <?php if(!empty($movie['TRAILERURL'])): ?>
                <a href="<?php echo htmlspecialchars($movie['TRAILERURL']); ?>" target="_blank" class="btn btn-sm btn-danger mt-2">
                  Watch Trailer
                </a>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="no-movies">No movies found in the <?php echo $genre; ?> genre.</p>
        <?php endif; ?>
      </div>
    </main>
    <div class="copyright-wrapper">
      &copy; 2025 Reel Good Pick &#124; All rights reserved
    </div>
  </body>
  <script src="js/script.js"></script>
</html>
