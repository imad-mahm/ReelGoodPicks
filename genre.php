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

// Get genre from URL parameter and sanitize it
$genre = isset($_GET['genre']) ? htmlspecialchars($_GET['genre']) : 'All';

// Prepare the query based on genre
if ($genre === 'All') {
    $query = "SELECT m.* FROM MOVIES m ORDER BY m.title";
    $stmt = $conn->prepare($query);
} else {
    $query = "SELECT DISTINCT m.* FROM MOVIES m 
              INNER JOIN MOVIE_GENRES mg ON m.id = mg.movie_id 
              WHERE mg.genre = ? 
              ORDER BY m.title";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $genre);
}

// Execute query
if(!$stmt->execute()){
  die("Query execution failed: " . $stmt->error);
}
$result = $stmt->get_result();
$movies = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $genre; ?> Movies - Reel Good Picks</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body>
    <nav class="nav-bar">
      <ul>
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="about.php">About Us</a></li>
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
                <a class="dropdown-item" href="random.php">Surprise Me</a>
              </li>
              <li>
                <a class="dropdown-item" href="watchlist.php">Watchlist</a>
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
      <h1><b>MOVIES</b></h1>
    </header>

    <main class="main-content">
      <h1 id="genreTitle"><?php echo $genre; ?> Movies</h1>
      <div id="movieList" class="movie-grid">
        <?php if (count($movies) > 0): ?>
          <?php foreach ($movies as $movie): ?>
            <div class="movie-card" onclick=" window.location.href='moviedetails.php?id=<?php echo $movie['ID']; ?>'">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
  </body>
</html>
<?php
$stmt->close();
$conn->close();
?>
