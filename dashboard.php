<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];
$fullname = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Movie Hub</title>
    <link rel="icon" type="image/x-icon" href="images/clapperboard.png" />

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body>
    <!-- Header -->
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
              <li><a class="dropdown-item" href="admin.php">Admin Dashboard</a></li>
            </ul>
          </div>
        </li>
      </ul>
    </nav>
    <header class="header">
      <h1><b>MOVIES</b></h1>
    </header>
    <!-- Main Content - Genre Section -->
    <main class="main-content">
      <div id="resultContainer" class="hidden">
        <h2>Recommended Movies</h2>
        <ul id="movieList"></ul>
      </div>
      <div id="genreGrid">
        <a href="genre.php?genre=All">
          <div class="genreCard">All Movies</div>
        </a>
        <a href="genre.php?genre=Romance">
          <div class="genreCard">Romance</div>
        </a>
        <a href="genre.php?genre=Comedy">
          <div class="genreCard">Comedy</div>
        </a>
        <a href="genre.php?genre=Sci-Fi">
          <div class="genreCard">Sci-Fi</div>
        </a>
        <a href="genre.php?genre=Horror">
          <div class="genreCard">Horror</div>
        </a>
        <a href="genre.php?genre=Thriller">
          <div class="genreCard">Thriller</div>
        </a>
        <a href="genre.php?genre=Action">
          <div class="genreCard">Action</div>
        </a>
        <a href="genre.php?genre=Adventure">
          <div class="genreCard">Adventure</div>
        </a>
        <a href="genre.php?genre=Drama">
          <div class="genreCard">Drama</div>
        </a>
      </div>
    </main>
    <div class="copyright-wrapper">
      &copy; 2025 Reel Good Pick &#124; All rights reserved
    </div>
  </body>
</html> 