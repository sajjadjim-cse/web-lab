<?php
// Database Connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'coffee_shop';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ✅ Check email domain
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@diu\.edu\.bd$/', $email)) {
        $error = "Only emails ending with @diu.edu.bd are allowed.";
    }
    // Validate Date
    elseif (!DateTime::createFromFormat('Y-m-d', $dob)) {
        $error = "Invalid date format.";
    } else {
        $dobFormatted = DateTime::createFromFormat('Y-m-d', $dob);
        $dateCode = $dobFormatted->format("dmY"); // ddmmyyyy

        // Get last auto number for today
        $checkCount = "SELECT COUNT(*) as total FROM users WHERE dob = ?";
        $stmt = $conn->prepare($checkCount);
        $stmt->bind_param("s", $dob);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $autoNumber = str_pad($result['total'] + 1, 3, '0', STR_PAD_LEFT);

        $serialNumber = $dateCode . '-' . $autoNumber;

        // Insert into DB
        $insertQuery = "INSERT INTO users (name, email, dob, serial_number, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssss", $name, $email, $dob, $serialNumber, $password);

        if ($stmt->execute()) {
            $success = "Registered successfully! Your Serial: $serialNumber";
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Registration</title>
   <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded shadow-md w-full max-w-md">
  <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

  <?php if ($success): ?>
    <div class="bg-green-100 text-green-700 p-2 mb-4 rounded"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= $error ?></div>
  <?php endif; ?>
  <form action="" method="POST" class="space-y-4">
    <div>
      <label class="block font-medium">Full Name</label>
      <input type="text" name="name" required class="w-full border rounded px-3 py-2" />
    </div>
    <div>
      <label class="block font-medium">Email</label>
      <input type="email" name="email" required pattern="^[a-zA-Z0-9._%+-]+@diu\.edu\.bd$" title="Email must end with @diu.edu.bd" placeholder="yourname@diu.edu.bd" class="w-full border rounded px-3 py-2"  />
    </div>
    <div>
      <label class="block font-medium">Date of Birth</label>
      <input type="date" name="dob" required class="w-full border rounded px-3 py-2" />
    </div>
    <div>
      <label class="block font-medium">Password</label>
      <input type="password" name="password" required pattern="^(?=.*[A-Z])(?=.*\d).{6,}$" title="Password must contain at least one uppercase letter and one number, and be at least 6 characters." class="w-full border rounded px-3 py-2" />
    </div>
    <div>
      <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        Register
      </button>
    </div>
     <p class="mt-4 text-center text-sm">
    Have an account? <a href="login.php" class="text-blue-600 hover:underline">Login here</a>
  </p>
  </form>
</div>

</body>
</html>
