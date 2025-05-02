<!-- <div id="sidebar" class="w-64 h-screen bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 p-4 space-y-2 fixed z-20 top-0 left-0 overflow-y-auto"> -->
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div id="sidebar" class="w-64 h-screen bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 space-y-2 fixed z-20 top-0 left-0 overflow-y-auto transition-all duration-300">
<h2 class="text-2xl font-semibold mb-6 text-blue-600 pt-3 text-center">Inventory</h2>

    <a href="dashboard.php" class="sidebar-link flex items-center gap-2 text-black dark:text-white hover:text-blue-600 transition <?php echo ($current_page == 'dashboard.php') ? 'bg-blue-100 text-blue-600 font-bold rounded-lg' : '';  ?>">
        <i class="fas fa-home"></i> Dashboard
    </a>

    <button class="sidebar-toggle submenu-toggle flex items-center justify-between w-full text-black dark:text-white hover:text-blue-600 transition" data-target="submenu-master">
        <span class="flex items-center gap-2"><i class="fas fa-cogs"></i> Master</span>
        <i class="fas fa-chevron-down"></i>
    </button>
    <div id="submenu-master" class="submenu <?php echo ($current_page == 'company.php' || $current_page == 'category.php') ? '' : 'hidden'; ?>  pl-5 space-y-2">
        <a href="company.php" class="sidebar-link flex items-center gap-2 text-black dark:text-white hover:text-blue-600 transition <?php echo ($current_page == 'company.php') ? 'bg-blue-100 text-blue-600 font-bold rounded-lg' : '';  ?>">
            <i class="fas fa-building"></i> Company
        </a>
        <a href="category.php" class="sidebar-link flex items-center gap-2 text-black dark:text-white hover:text-blue-600 transition <?php echo ($current_page == 'category.php') ? 'bg-blue-100 text-blue-600 font-bold rounded-lg' : '';  ?>">
            <i class="fas fa-tags"></i> Category
        </a>
    </div>

    <a href="categoryassign.php" class="sidebar-link flex items-center gap-2 text-black dark:text-white hover:text-blue-600 transition <?php echo ($current_page == 'categoryassign.php') ? 'bg-blue-100 text-blue-600 font-bold rounded-lg' : '';  ?>">
        <i class="fas fa-tasks"></i> Category Assign
    </a>

    <a href="serialnoassign.php" class="sidebar-link flex items-center gap-2 text-black dark:text-white hover:text-blue-600 transition <?php echo ($current_page == 'serialnoassign.php') ? 'bg-blue-100 text-blue-600 font-bold rounded-lg' : '';  ?>">
        <i class="fas fa-list-ol"></i> Serial No. Assign
    </a>

    <a href="stock.php" class="sidebar-link flex items-center gap-2 text-black dark:text-white hover:text-blue-600 transition <?php echo ($current_page == 'stock.php') ? 'bg-blue-100 text-blue-600 font-bold rounded-lg' : '';  ?>">
        <i class="fas fa-box"></i> Stock
    </a>

    <a href="assignasset.php" class="sidebar-link flex items-center gap-2 text-black dark:text-white hover:text-blue-600 transition <?php echo ($current_page == 'assignasset.php') ? 'bg-blue-100 text-blue-600 font-bold rounded-lg' : '';  ?>">
        <i class="fas fa-laptop-house"></i> Assign Assets
    </a>
</div>
