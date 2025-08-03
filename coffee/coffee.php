<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if not logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user_email'];

// DB connection
$conn = new mysqli("localhost", "root", "", "coffee_shop");
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// Handle Buy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy'])) {
    $coffee_title = $_POST['title'];
    $coffee_id = $_POST['coffee_id'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO purchase (user_email, coffee_id, coffee_title, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sisd", $user_email, $coffee_id, $coffee_title, $amount);

    if ($stmt->execute()) {
        $message = "☕ Purchase successful!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

// Fetch coffees
$apiUrl = "https://api.sampleapis.com/coffee/hot";
$response = file_get_contents($apiUrl);
$coffees = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Coffee Menu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
 <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
  <h1 class="text-xl font-bold">☕ Coffee Menu</h1>
  <div class="space-x-4">
    <span class="text-sm text-gray-500">Logged in as <?= htmlspecialchars($user_email) ?></span>
    <a href="logout.php" class="text-red-500 hover:underline">Logout</a>
  </div>
</nav>

<div class="max-w-7xl mx-auto p-6">
  <?php if (isset($message)): ?>
    <div class="mb-4 text-white px-4 py-2 rounded <?= strpos($message, 'successful') !== false ? 'bg-green-500' : 'bg-red-500' ?>">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php foreach ($coffees as $coffee): ?>
      <div class="bg-white shadow rounded overflow-hidden">
        <img src="<?= htmlspecialchars($coffee['image']) ?>" alt="<?= htmlspecialchars($coffee['title']) ?>" class="w-full h-48 object-cover">
        <div class="p-4">
          <h2 class="text-xl font-bold mb-2"><?= htmlspecialchars($coffee['title']) ?></h2>
          <p class="text-sm text-gray-600 mb-2"><?= htmlspecialchars($coffee['description']) ?></p>
          <ul class="text-xs text-gray-500 mb-3">
            <?php foreach ($coffee['ingredients'] as $ingredient): ?>
              <li>• <?= htmlspecialchars($ingredient) ?></li>
            <?php endforeach; ?>
          </ul>

          <form method="POST" class="space-y-2">
            <input type="hidden" name="coffee_id" value="<?= $coffee['id'] ?>">
            <input type="hidden" name="title" value="<?= htmlspecialchars($coffee['title']) ?>">
            <input type="hidden" name="amount" value="<?= rand(2, 6) + 0.99 ?>">
            <input type="email" name="email" value="<?= htmlspecialchars($user_email) ?>" readonly class="w-full px-3 py-1 border rounded text-sm bg-gray-100 cursor-not-allowed" />
            <button type="submit" name="buy" class="w-full bg-yellow-400 text-black px-4 py-2 rounded hover:bg-yellow-300 font-semibold text-sm">
              Buy Now
            </button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
