<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: ../frontend/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Inventory System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #f4f4f4;
            font-family: 'Inter', sans-serif;
        }
        .login-box {
            margin-top: 100px;
            max-width: 400px;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px #ccc;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex justify-center items-center min-h-screen bg-gradient-to-r to-indigo-600">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md sm:max-w-sm">
            <h4 class="text-center text-2xl font-semibold text-gray-800 mb-6">Inventory System Login</h4>

            <form class="space-y-4" action="validate.php" method="POST">
                <!-- Employee ID -->
                <div class="form-group">
                    <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee ID</label>
                    <input type="text" id="employee_id" name="employee_id" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Submit Button -->
                <button class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" type="submit">Login</button>
            </form>

            <!-- Optional Footer -->
            <div class="mt-4 text-center text-sm text-gray-500">
                <p>&copy; 2025 Inventory System. All Rights Reserved.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional for alerts/modals if needed later) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
