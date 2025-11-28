<?php
/**
 * Admin Home View - Main Dashboard Router
 * 
 * This view ONLY handles display logic - no business logic here.
 * All form handling and CRUD operations are done in AdminController.
 * 
 * Data passed from controller in $data array:
 * - $data['title']: Page title
 * - $data['action']: Current action (menu, user, coupon, branch, inventory, orders)
 * - $data['products'], $data['accounts'], $data['coupons'], etc.: Data to display
 */

// Get action from controller data
$action = $data['action'] ?? 'menu';
$role = $_SESSION['user']['Role'] ?? 2;
$userID = $_SESSION['user']['ID'] ?? 0;
$name = $_SESSION['user']['Name'] ?? 'Guest';
?>

<!-- Head html -->
<?php include_once 'app/views/layout/adminHead.php'; ?>

<!-- Sidebar -->
<?php include_once 'app/views/layout/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <?php include_once 'app/views/layout/headerAdmin.php'; ?>  

    <div class="content-wrapper">
        <?php  
        // Route to appropriate view based on action
        switch ($action) {
            case 'branch':
                include_once 'app/views/admin/home/branch.php';
                break;
            case 'menu':
                include_once 'app/views/admin/home/menu.php';
                break;
            case 'user':
                include_once 'app/views/admin/home/userManager.php';
                break;
            case 'coupon':
                include_once 'app/views/admin/home/couponManager.php';
                break;
            case 'inventory':
                include_once 'app/views/admin/home/inventory.php';
                break;
            case 'orders':
                include_once 'app/views/admin/home/orders.php';
                break;
            default:
              include_once 'app/views/admin/home/menu.php';
              break;
          }
      ?>

    </div>
</main>
  <?php include_once 'app/views/layout/adminBottom.php'; ?>