<?php
session_start();
require 'dbconnection.php';

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // Get id, password, and role
  $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id, $hashed_password, $role);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
      // ✅ Set session variables
      $_SESSION['user_id'] = $user_id;
      $_SESSION['username'] = $username;
      $_SESSION['role'] = $role;

      $login_time = date('Y-m-d H:i:s');
      $stmt2 = $conn->prepare("INSERT INTO sessions (username, login_time) VALUES (?, ?)");
      $stmt2->bind_param("ss", $username, $login_time);
      $stmt2->execute();
      $_SESSION['session_id'] = $stmt2->insert_id;

      header("Location: dashboard.php");
      exit();
    } else {
      $message = "Invalid password.";
    }
  } else {
    $message = "User not found.";
  }

  $stmt->close();
}
$conn->close();
?>


<?php include 'header.php' ?>
<?php include 'navbar.php' ?>

<!-- Intro Section -->
<section class="bg-[#0653a9] h-screen flex items-center">
  <div class="text-white text-7xl md:text-8xl font-light leading-snug text-left px-8">
    Empowering <span class="font-bold">Business</span><br>
    with Innovative<br>
    Digital Solutions
  </div>
</section>

<!-- About Us Section -->
<section id="about" class="min-h-screen flex flex-col justify-center items-center bg-gray-100 px-8 py-16">
  <h2 class="text-4xl font-bold mb-12 text-blue-600 text-center">About Us</h2>

  <div class="w-full max-w-6xl flex flex-col md:flex-row gap-8">
    <!-- Mission -->
    <div class="flex-1 bg-white p-8 shadow-lg rounded-lg">
      <h3 class="text-2xl font-semibold mb-4 text-blue-600">Our Mission</h3>
      <p class="text-gray-700 text-lg leading-relaxed">
        To deliver excellent transportation and logistics services with integrity,
        punctuality, and a commitment to customer satisfaction while empowering our workforce.
      </p>
    </div>

    <!-- Vision -->
    <div class="flex-1 bg-white p-8 shadow-lg rounded-lg">
      <h3 class="text-2xl font-semibold mb-4 text-blue-600">Our Vision</h3>
      <p class="text-gray-700 text-lg leading-relaxed">
        To be the leading provider of safe, reliable, and efficient trucking solutions nationwide,
        fostering sustainable growth for our clients and communities.
      </p>
    </div>
  </div>
</section>

<!-- Services Section -->
<section id="services" class="min-h-screen bg-[#0653a9] text-white py-16 px-8 flex flex-col justify-center">
  <div class="max-w-6xl mx-auto text-center">
    <h2 class="text-4xl font-bold mb-4">Our Services</h2>
    <p class="mb-8 text-lg max-w-3xl mx-auto">
      Explore our diverse range of services designed to meet your specific business needs and drive growth through digital innovation.
    </p>
    <hr class="border-white border-t-2 w-full mb-12" />

    <div class="grid md:grid-cols-2 gap-10 text-left">
      <!-- Custom Solutions -->
      <div class="flex gap-4">
        <img src="assets/customIcon.png" alt="Custom Solutions" class="w-16 h-16 bg-white p-1" />
        <div>
          <h3 class="text-lg font-semibold">Custom Solutions</h3>
          <p class="italic text-gray-200 mb-2">Tailored for your business</p>
          <p class="text-sm text-gray-100">
            Our custom digital solutions are built to fit your unique requirements, ensuring seamless integration and optimal performance.
          </p>
        </div>
      </div>

      <!-- Truck Link -->
      <div class="flex gap-4">
        <img src="assets/TRUCKLINK.png" alt="Truck Link" class="w-16 h-16 bg-white p-1" />
        <div>
          <h3 class="text-lg font-semibold">Truck - Link</h3>
          <p class="italic text-gray-200 mb-2">Efficient Management</p>
          <p class="text-sm text-gray-100">
            Optimize your trucking business with Truck Link, our innovative platform that revolutionizes trucking management.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact / Footer Section -->
<footer id="contact" class="bg-gradient-to-r from-gray-300 to-white text-black px-10 py-10">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between gap-10">
    <!-- Left -->
    <div class="flex-1">
      <h2 class="text-lg font-bold text-blue-800">Your Growth Partner</h2>
      <p class="mt-4 mb-1">Email us</p>
      <div class="w-36 border-b-2 border-black"></div>
    </div>

    <!-- Middle -->
    <div class="flex-1 space-y-2">
      <p>123-1234-1234</p>
      <p>JRZ.Nexus@gmail.com</p>
      <p>4025 Cabuyao, Laguna<br />Philippines</p>
      <div class="flex space-x-4 mt-4 text-xl">
        <a href="#" class="hover:text-blue-800"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="hover:text-blue-800"><i class="fab fa-twitter"></i></a>
        <a href="#" class="hover:text-blue-800"><i class="fab fa-instagram"></i></a>
        <a href="#" class="hover:text-blue-800"><i class="fab fa-youtube"></i></a>
        <a href="#" class="hover:text-blue-800"><i class="fab fa-tiktok"></i></a>
      </div>
    </div>

    <!-- Right -->
    <div class="flex-1 space-y-2">
      <p><a href="#" class="hover:underline">Privacy Policy</a></p>
      <p><a href="#" class="hover:underline">Terms and Conditions</a></p>
      <p><a href="#" class="hover:underline">Refund Policy</a></p>
    </div>
  </div>
</footer>

<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
  <div class="bg-white p-8 rounded-2xl w-[90%] max-w-sm shadow-2xl border-t-4 border-blue-600 relative animate-fadeInUp">

    <!-- Close Button -->
    <button onclick="closeLoginModal()" class="absolute top-3 right-4 text-gray-400 hover:text-red-500 text-2xl font-bold">
      &times;
    </button>

    <!-- Logo and Title -->
    <div class="flex flex-col items-center mb-6">
      <img src="assets/trulink.png" alt="Truck Link Logo" class="h-20 w-auto object-contain mb-2" />
    </div>

    <?php if ($message): ?>
      <p class="text-red-500 text-sm text-center mb-4"><?= htmlspecialchars($message) ?></p>
    <?php elseif (isset($_GET['registered'])): ?>
      <p class="text-green-500 text-sm text-center mb-4">Registration successful! Please log in.</p>
    <?php endif; ?>

    <form id="loginForm" class="space-y-4">
      <div>
        <label class="block text-sm text-gray-600 mb-1">Username</label>
        <input type="text" name="username" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
      </div>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Password</label>
        <input type="password" name="password" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
      </div>
      <button type="submit"
        class="w-full bg-blue-600 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-700 transition">Login</button>
    </form>
    <p id="loginMessage" class="text-center mt-4 text-sm font-semibold text-gray-700"></p>
  </div>
</div>

<!-- Enhanced Message Modal -->
<div id="messageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
  <div class="bg-white p-6 rounded-2xl w-[90%] max-w-sm shadow-2xl border-t-4 border-blue-600 relative animate-fadeInUp">
    <!-- Icon -->
    <div class="flex justify-center mb-4">
      <div id="messageIcon"
        class="w-14 h-14 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 text-2xl">
        <i class="fas fa-info-circle"></i>
      </div>
    </div>

    <!-- Message Text -->
    <p id="messageText" class="text-center text-lg font-medium text-gray-800"></p>
  </div>
</div>

<script>
  function openLoginModal() {
    document.getElementById('loginModal').classList.remove('hidden');
  }

  function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
  }

  document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // Prevent page refresh

    const formData = new FormData(this);

    try {
      const response = await fetch('login_handler.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      const messageModal = document.getElementById('messageModal');
      const messageText = document.getElementById('messageText');

      messageText.textContent = result.message;
      messageText.className = result.success ? 'text-green-600 text-center text-lg font-semibold' : 'text-red-600 text-center text-lg font-semibold';
      messageModal.classList.remove('hidden');

      setTimeout(() => {
        messageModal.classList.add('hidden');
        if (result.success) {
          window.location.href = 'dashboard.php';
        }
      }, 2000); // 2 seconds

    } catch (error) {
      console.error('Error:', error);
      const messageModal = document.getElementById('messageModal');
      const messageText = document.getElementById('messageText');
      messageText.textContent = 'Something went wrong.';
      messageText.className = 'text-red-600 text-center text-lg font-semibold';
      messageModal.classList.remove('hidden');

      setTimeout(() => {
        messageModal.classList.add('hidden');
      }, 2000);
    }
  });
</script>