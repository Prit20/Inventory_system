<!-- <body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100"> -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- <div class="flex flex-col h-screen"> -->
    <?php include '../includes/header.php'; ?>

    <!-- <main class="flex-1 p-6 overflow-y-auto"> -->
  <div class="main-content transition-all duration-300">

      <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
      <p>Welcome, <?= $_SESSION['username'] ?? 'User'; ?>!</p>
</div>
  <!-- </div> -->
<!-- </body> -->
<!-- </html> -->
<?php include '../includes/footer.php'; ?>

