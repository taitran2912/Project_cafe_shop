<?php
  
  session_start();
  $role = $_SESSION['user']['Role'];
  $userID = $_SESSION['user']['ID'];
  $name = $_SESSION['user']['Name'];

  $uri = $_SERVER['REQUEST_URI'];
  $base = '/Project_cafe_shop/admin/';
  // Remove query string to get clean action
  $uriWithoutQuery = strtok($uri, '?');
  $action = trim(str_replace($base, '', $uriWithoutQuery), '/');
?>
<!-- Head html -->
  <?php include_once 'app/views/layout/adminHead.php'; ?>
<!-- Sidebar -->
  <?php include_once 'app/views/layout/sidebar.php'; ?>
<!-- Main Content -->
 <!-- <h1>
  <?php echo $role ?>
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
            default:
              include_once 'app/views/admin/home/couponManager.php';
              break;
          }
      ?>

    </div>
</main>
  <?php include_once 'app/views/layout/adminBottom.php'; ?>