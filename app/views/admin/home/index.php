<?php
  session_start();
  $role = $_SESSION['user']['Role'];
  $userID = $_SESSION['user']['ID'];
  $name = $_SESSION['user']['Name'];
  switch ($role) {
    case 1:
      $roleName = "Admin";
      break;
    case 2:
      $roleName = "Nhân viên";
      break;
    case 3:
      $roleName = "Quản lý";
      break;
  }

  $uri = $_SERVER['REQUEST_URI'];
  $base = '/Project_cafe_shop/admin/';
  $action = trim(str_replace($base, '', $uri), '/');
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
          switch ($action) {
            case 'branch':
              include_once 'app/views/admin/home/branch.php';
              break;
            case 'menu':
              include_once 'app/views/admin/home/menu.php'; // Tuỳ theo action để include file tương ứng
              break;
            default:
              
              break;
          }
      ?>

    </div>
</main>
  <?php include_once 'app/views/layout/adminFooter.php'; ?>