<?php
session_start();

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

// Get movie ID from URL parameter and sanitize it
$movie_id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;

// Get movie details
$query = "SELECT * 
          FROM MOVIES m 
          LEFT JOIN MOVIE_GENRES mg ON m.id = mg.movie_id 
          WHERE m.id = ? 
          GROUP BY m.id";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_all(MYSQLI_ASSOC)[0];

// Get actors and their roles for the movie
$actors_query = "SELECT a.name AS actor_name, ma.role AS actor_role
                 FROM MOVIE_ACTORS ma
                 INNER JOIN ACTORS a ON ma.actor_id = a.id
                 WHERE ma.movie_id = ?";
$actors_stmt = $conn->prepare($actors_query);
$actors_stmt->bind_param("i", $movie_id);
$actors_stmt->execute();
$actors_result = $actors_stmt->get_result();
$actors = $actors_result->fetch_all(MYSQLI_ASSOC);

$actors_stmt->close();

// Check if movie is in user's watchlist
$in_watchlist = false;
if (isset($_SESSION['id'])) {
    $check_watchlist = $conn->prepare("SELECT * FROM WATCHLIST WHERE user_id = ? AND movie_id = ?");
    $check_watchlist->bind_param("ii", $_SESSION['id'], $movie_id);
    $check_watchlist->execute();
    $in_watchlist = $check_watchlist->get_result()->num_rows > 0;
    $check_watchlist->close();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($movie['TITLE']); ?> - Reel Good Picks</title>
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
      <h1><b>MOVIE DETAILS</b></h1>
    </header>

    <main class="main-content">

      <div class="movie-details-container">
        <div class="movie-poster">
          <img src="<?php echo htmlspecialchars($movie['POSTERURL']); ?>" alt="<?php echo htmlspecialchars($movie['TITLE']); ?>">
        </div>
        
        <div class="movie-info">
          <h1><?php echo htmlspecialchars($movie['TITLE']); ?></h1>
          
          <div class="movie-meta">
            <p><strong>Year:</strong> <?php echo htmlspecialchars($movie['RELEASE_YEAR']); ?></p>
            <p><strong>Director:</strong> <?php echo htmlspecialchars($movie['DIRECTOR']); ?></p>
            <p><strong>Duration:</strong> <?php echo htmlspecialchars($movie['DURATION']); ?> minutes</p>
            <p><strong>Language:</strong> <?php echo htmlspecialchars($movie['LANGUAGE']); ?></p>
            <p><strong>Country:</strong> <?php echo htmlspecialchars($movie['COUNTRY']); ?></p>
            <p><strong>Box Office:</strong> $<?php echo number_format(htmlspecialchars($movie['BOX_OFFICE'])); ?></p>
            <p><strong>Genres:</strong> <?php echo htmlspecialchars($movie['GENRE']); ?></p>
          </div>

          <div class="movie-description">
            <h3>Description</h3>
            <p><?php echo htmlspecialchars($movie['DESCRIPTION']); ?></p>
          </div>

          <div class="movie-cast">
            <h3>Cast</h3>
            <ul>
              <?php foreach ($actors as $actor): ?>
                <li><?php echo htmlspecialchars($actor['actor_name']); ?> as <?php echo htmlspecialchars($actor['actor_role']); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>

          <div class="movie-actions">
            <?php if(isset($_SESSION['id'])): ?>
              <?php if(!$in_watchlist): ?>
                <a href="add_to_watchlist.php?movie_id=<?php echo $movie['ID']; ?>" class="btn btn-primary">
                  Add to Watchlist
                </a>
              <?php else: ?>
                <a href="remove_from_watchlist.php?movie_id=<?php echo $movie['ID']; ?>" class="btn btn-danger">
                  Remove from Watchlist
                </a>
              <?php endif; ?>
            <?php endif; ?>
            
            <?php if(!empty($movie['TRAILERURL'])): ?>
              <a href="<?php echo htmlspecialchars($movie['TRAILERURL']); ?>" target="_blank" class="btn btn-danger">
                Watch Trailer
              </a>
            <?php endif; ?>
          </div>
        </div>
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