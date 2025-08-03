<?php
session_start();

// DB Connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'coffee_shop';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST["identifier"]);

    // Query users where email = ? OR serial_number = ?
    $query = "SELECT * FROM users WHERE email = ? OR serial_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Password validation removed
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_email"] = $user["email"];

        header("Location: home.php");
        exit;
    } else {
        $error = "❌ No user found with that email or serial number!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded shadow-md w-full max-w-md">
  <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

  <?php if (!empty($error)): ?>
    <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form action="" method="POST" class="space-y-4">
    <div>
      <label class="block font-medium">Email or Serial Number</label>
      <input type="text" name="identifier" required class="w-full border rounded px-3 py-2" placeholder="Enter email or serial number" />
    </div>
    <div>
      <label class="block font-medium">Password</label>
      <input type="password" name="password" required class="w-full border rounded px-3 py-2" />
    </div>
    <div>
      <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        Login
      </button>
    </div>
  </form>

  <p class="mt-4 text-center text-sm">
    Don’t have an account? <a href="register.php" class="text-blue-600 hover:underline">Register here</a>
  </p>
</div>

</body>
</html>
