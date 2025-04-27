<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
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
$stmt->close();

// Handle form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize variables
    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (empty($fullname)) {
        $error = "Full name is required.";
    } elseif (empty($username)) {
        $error = "Username is required.";
    } else {
        // Check if username is already taken (excluding current user)
        $check_username = $conn->prepare("SELECT id FROM USER WHERE username = ? AND id != ?");
        $check_username->bind_param("si", $username, $user_id);
        $check_username->execute();
        if ($check_username->get_result()->num_rows > 0) {
            $error = "Username is already taken.";
        }
        $check_username->close();

        if (empty($error)) {
            // Start transaction
            $conn->begin_transaction();

            try {
                // Update basic profile info
                $update_profile = $conn->prepare("UPDATE USER SET fullname = ?, username = ?, bio = ? WHERE id = ?");
                $update_profile->bind_param("sssi", $fullname, $username, $bio, $user_id);
                $update_profile->execute();
                $update_profile->close();

                // Handle password change if provided
                if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
                    // Verify current password
                    $verify_password = $conn->prepare("SELECT hashedpassword FROM USER WHERE id = ?");
                    $verify_password->bind_param("i", $user_id);
                    $verify_password->execute();
                    $hashed_password = $verify_password->get_result()->fetch_assoc()['hashedpassword'];
                    $verify_password->close();

                    if (password_verify($current_password, $hashed_password)) {
                        if ($new_password === $confirm_password) {
                            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                            $update_password = $conn->prepare("UPDATE USER SET hashedpassword = ? WHERE id = ?");
                            $update_password->bind_param("si", $new_hashed_password, $user_id);
                            $update_password->execute();
                            $update_password->close();
                        } else {
                            throw new Exception("New passwords do not match.");
                        }
                    } else {
                        throw new Exception("Current password is incorrect.");
                    }
                }

                // Commit transaction
                $conn->commit();
                $success = "Profile updated successfully!";
                
                // Update session variables
                $_SESSION['fullname'] = $fullname;
                $_SESSION['username'] = $username;

            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                $error = $e->getMessage();
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile - Reel Good Picks</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body>
    <nav class="nav-bar">
      <ul>
        <li><a href="index.php">Home</a></li>
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
              <li><a class="dropdown-item" href="test.php">Test Me</a></li>
            </ul>
          </div>
        </li>
      </ul>
    </nav>

    <header class="header">
      <h1><b>Edit Profile</b></h1>
    </header>

    <main class="main-content">
      <?php if ($success): ?>
        <div class="alert alert-success" role="alert">
          <?php echo htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <div class="container mt-4">
        <form action="edit_profile.php" method="POST" class="needs-validation" novalidate>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullname" name="fullname" 
                       value="<?php echo htmlspecialchars($user['FULLNAME']); ?>" required>
                <div class="invalid-feedback">
                  Please provide your full name.
                </div>
              </div>

              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" 
                       value="<?php echo htmlspecialchars($user['USERNAME']); ?>" required>
                <div class="invalid-feedback">
                  Please choose a username.
                </div>
              </div>

              <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="3"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password">
                <div class="form-text">Leave blank if you don't want to change your password.</div>
              </div>

              <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
              </div>

              <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
              </div>
            </div>
          </div>

          <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <a href="profile.php" class="btn btn-secondary me-md-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </main>

    <div class="copyright-wrapper">
      &copy; 2024 Reel Good Pick &#124; All rights reserved
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html> 