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

// Fetch user data
$user_id = $_SESSION['id'];
$query = "SELECT * FROM USER WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch user's watchlist
$watchlist_query = "SELECT m.* FROM MOVIES m 
                   INNER JOIN WATCHLIST w ON m.id = w.movie_id 
                   WHERE w.user_id = ?";
$watchlist_stmt = $conn->prepare($watchlist_query);
$watchlist_stmt->bind_param("i", $user_id);
$watchlist_stmt->execute();
$watchlist_result = $watchlist_stmt->get_result();
$watchlist_movies = $watchlist_result->fetch_all(MYSQLI_ASSOC);




?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile - Reel Good Picks</title>
    <link rel="icon" type="image/x-icon" href="images/Profile.png" />
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
      <div class="user-info">
        <div class="dropdown">
          <button class="btn btn-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span>Welcome, <?php echo htmlspecialchars($user['FULLNAME']); ?></span>
          </button>
          <ul class="dropdown-menu" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
            <li><a class="dropdown-item" href="watchlist.php">My Watchlist</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
      <h1><b>Profile</b></h1>
    </header>
    <main class="profile-page" style="text-align: center">
      <!-- User Information -->
      <div class="profile-info">
        <img
          src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJQAAACUCAMAAABC4vDmAAAAMFBMVEXk5ueutLeor7Lf4ePn6eqrsbW5vsHIzM7a3d60ur3BxsjR1NbN0dPX2tzr7O29wsX2DjRMAAADaUlEQVR4nO2bW3LkIAwADYi3be5/25iZZB4bxyDZgqkt+ivZn+0SQgahTNNgMBgMBoPBYDAYDAaDwWCaAGBSG/mn3i53AFQMxt8xdpm6ewE466XU4getpZlVVy9YjHgKPcRE6Ke1KclfRnct2UkLprATpWe05g5W4PzfShmZVHOneGh0D1ZjK5j/yKZ3lpZLCPZ46R7Bcu2sKuN0i1Uzp1gXpxvN8qpeSQjTyMkgAiV0aJFWMGOctnrVpLZXJ/k3DRYQAi5Q2wJGdqkFqZThXj98oHKouK2wGZVhzqra78s/oXK8VobgxF2rHMVpY+WUipSU2goo5/pBoqTUtn6cZ+OV5sScVLTV4y0Kjhgp4fmOVajT3TuMUshTyxPG8kmr5xnGmnBCiu8C8b9JMS7fRyY6vSQwSi0fWDwn9YmfGaBKBUap1dOctGU8JVC3H29LaCGePHnvWKT104lVCgIpUMwXd1JR4KxSGcr+Y917NwhFXTIrTYQ7coNeHjhsVnFnVGZFtTyZL6IPFM7Js/YRfgBcWWduAz2sEN082e55prrPwV+iXii89T3i1NKp8tWhzWsDzqpxnDKlO6AW7J3q38BymFjSdHlvP3pu12LuYHRjdUHuaWlhew5xgApe6Fex7RffLUoPrWmxRkipM1KKNLv+IzjfuBjnuOTv3GcYAawvQN8Rqvy/K7dEG5L5Po4ak4KdF9dpvAtWtdhkvL5l02ue538RPoWoYG0oBpOKQUh9WNJz3pvZqSYRg9VZL3bL017B8iFyxwsmZ2uFniFLC2MpBYh7024VWt4yVQpQ9jiLDr1kYGhaHw+71WiJdHGTaosSMpP2kOnKWwTMlWfyAvq63ic4T+2//ta66L4M9iqju1Y6Xx+Kk5N4q9NTJhDP7bl9rZOZZS/Lple2S8UJJ+IYQhEt6ImF7EShoJasq1P8DeIjBGecMoRYAbeT0Ohsh8Cy797AdmjpT9gItEEtIL4vTULiPoTEx0YsGpHslLlJGr5eqs3iZRCN2tTKSVTPMNGnDwjoVPcgQX1SJ1pVherE7AhJqq6t3Wzr3amq67hHqvPImtMxceiVjimn+koaWT5DTaq3zahMcf2A8ucC5yhXdfqEG51UWrx23+InvphSLb97PxQz3cv2FN++VQeKyzcYDAaDwaA9XxcLKh2A6JUdAAAAAElFTkSuQmCC"
          alt="Profile Picture"
          class="profile-pic"
          width="100px"
        />
        <h2><?php echo htmlspecialchars($user['FULLNAME']); ?></h2>
        <p>Email: <?php echo htmlspecialchars($user['EMAIL']); ?></p>
        <p>Username: <?php echo htmlspecialchars($user['USERNAME']); ?></p>
        <?php if (!empty($user['BIO'])): ?>
          <p>Bio: <?php echo htmlspecialchars($user['BIO']); ?></p>
        <?php endif; ?>
      </div>

      <!-- Watchlist -->
      <div class="watchlist">
        <h3>My Watchlist</h3>
        <div class="movie-grid">
          <?php if (count($watchlist_movies) > 0): ?>
            <?php foreach ($watchlist_movies as $movie): ?>
              <div class="movie-card">
                <img src="<?php echo htmlspecialchars($movie['POSTERURL']); ?>" alt="<?php echo htmlspecialchars($movie['TITLE']); ?>">
                <h3><?php echo htmlspecialchars($movie['TITLE']); ?></h3>
                <p><strong>Year:</strong> <?php echo htmlspecialchars($movie['RELEASE_YEAR']); ?></p>
                <p><strong>Director:</strong> <?php echo htmlspecialchars($movie['DIRECTOR']); ?></p>
                <button class="btn btn-sm btn-danger remove-from-watchlist" id="remove-<?php echo $movie['ID']; ?>">
                  Remove from Watchlist
                </button>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="no-movies">Your watchlist is empty. Add some movies to get started!</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Favorite Movies -->
      <div class="favorite-movies">
        <h3>Favorite Movies</h3>
        <div class="movie-grid">
          <?php
          // Query to get user's favorite movies
          $stmt = $conn->prepare("
            SELECT m.* 
            FROM movies m
            INNER JOIN user_favorites uf ON m.id = uf.movie_id 
            WHERE uf.user_id = ?
          ");
          $stmt->bind_param("i", $_SESSION['id']);
          $stmt->execute();
          $favorite_movies = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
          ?>

          <?php if (count($favorite_movies) > 0): ?>
            <?php foreach ($favorite_movies as $movie): ?>
              <div class="movie-card">
                <img src="<?php echo htmlspecialchars($movie['POSTERURL']); ?>" alt="<?php echo htmlspecialchars($movie['TITLEe']); ?>">
                <h3><?php echo htmlspecialchars($movie['TITLE']); ?></h3>
                <p><strong>Year:</strong> <?php echo htmlspecialchars($movie['RELEASE_YEAR']); ?></p>
                <p><strong>Director:</strong> <?php echo htmlspecialchars($movie['DIRECTOR']); ?></p>
                <button class="btn btn-sm btn-danger remove-from-favorites" data-movie-id="<?php echo $movie['ID']; ?>">
                  Remove from Favorites
                </button>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="no-movies">You haven't added any favorite movies yet!</p>
          <?php endif; ?>
        </div>
      </div>

      <button class="btn btn-primary" onclick="window.location.href='logout.php'">Logout</button>

      <!-- Account Settings -->
      <div class="account-settings">
        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
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
$watchlist_stmt->close();
$conn->close();
?>