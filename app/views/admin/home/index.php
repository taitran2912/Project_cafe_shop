<?php
  session_start();
  $role = $_SESSION['user']['Role'];
  $userID = $_SESSION['user']['ID'];

  $uri = $_SERVER['REQUEST_URI'];
  $base = '/Project_cafe_shop/admin/';
  $action = trim(str_replace($base, '', $uri), '/');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Chi nhánh - Cafe Management</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/sidebar.css">
</head>
<body>
    <div class="dashboard">
    <!-- Sidebar -->
    <?php 
      include_once 'app/views/layout/sidebar.php'; 
    ?>

    <!-- Main Content -->
    <main class="main-content">
<?php 
  include_once 'app/views/layout/headerAdmin.php'; 
?>  

      <div class="content-wrapper">
<?php  
  switch ($action) {
    case 'branch':
      include_once 'app/views/admin/home/branch.php';
      break;
    default:
      include_once 'app/views/admin/home/content.php';
      break;
  }

?>
      </div>
    </main>
  </div>

  <script>
    // Toggle sidebar on mobile
    document.querySelector('.btn-menu-toggle').addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('active');
    });

    // Action button handlers
    document.querySelectorAll('.btn-view').forEach(btn => {
      btn.addEventListener('click', function() {
        alert('Xem chi tiết chi nhánh');
      });
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', function() {
        alert('Sửa thông tin chi nhánh');
      });
    });

    document.querySelectorAll('.btn-delete').forEach(btn => {
      btn.addEventListener('click', function() {
        if(confirm('Bạn có chắc muốn xóa chi nhánh này?')) {
          alert('Đã xóa chi nhánh');
        }
      });
    });
  </script>
</body>
</html>