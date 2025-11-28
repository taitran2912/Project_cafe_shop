<?php
session_start();
$role = $_SESSION['user']['Role'];
$userID = $_SESSION['user']['ID'];
$name = $_SESSION['user']['Name'];
  
$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$pos = strpos($uriPath, '/admin/');

// Lấy chuỗi sau "/admin/"
$action = $pos !== false ? substr($uriPath, $pos + strlen('/admin/')) : '';

// include_once 'app/views/layout/xuly.php';
?>
<!-- Head html -->
  <?php include_once 'app/views/layout/adminHead.php'; ?>
<!-- Sidebar -->
  <?php include_once 'app/views/layout/sidebar.php'; ?>
<!-- Main Content -->
 <!-- <h1>
  <?php echo $action ?>
</h1> -->
<main class="main-content">

  <?php include_once 'app/views/layout/headerAdmin.php'; ?>  

    <div class="content-wrapper">

      <?php  
          switch ($action) {
            case 'branch':
              include_once 'app/views/admin/home/branch.php';
              break;
            case 'menu':
              include_once 'app/views/admin/home/menu.php'; // Tuỳ theo action để include file tương ứng
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
            case 'table':
              include_once 'app/views/admin/home/table.php';
              break;
            case 'dashboard':
              include_once 'app/views/admin/home/dashboard.php';
              break;
            case 'POS':
              include_once 'app/views/admin/home/POS.php';
              break;
            default:
              include_once 'app/views/admin/home/dashboard.php';
              break;
          }
      ?>

    </div>
</main>
  <?php include_once 'app/views/layout/adminBottom.php'; ?>