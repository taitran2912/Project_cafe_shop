<aside class="sidebar">
  <div class="logo">
    <i class="fas fa-coffee"></i>
    <span>Cafe Manager</span>
  </div>

  <?php
  // ===== LẤY URL HIỆN TẠI TỪ ROUTER =====
  $url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
  $urlParts = explode('/', $url);

  // controller/action hiện tại
  $currentController = strtolower($urlParts[0] ?? 'home');
  $currentAction     = strtolower($urlParts[1] ?? 'index');

  // ===== LẤY QUYỀN NGƯỜI DÙNG TỪ SESSION =====

  // ===== DANH SÁCH MENU =====
  $menuItems = [
    [
      "title" => "Dashboard",
      "icon"  => "fas fa-chair",
      "link"  => "admin/dashboard",
      "roles" => [0, 1, 2, 3]
    ],
    // Quản lý chuỗi
    [
      "title" => "Quản lý thực đơn",
      "icon"  => "fas fa-utensils",
      "link"  => "admin/menu",
      "roles" => [0, 1]
    ],
    [
      "title" => "Quản lý chi nhánh",//tí sửa
      "icon"  => "fas fa-store",
      "link"  => "admin/branch",
      "roles" => [0, 1]
    ],
    [
      "title" => "Quản lý nhân viên",
      "icon"  => "fas fa-user-shield",
      "link"  => "admin/user",
      "roles" => [0, 1]
    ],
    [
      "title" => "Quản lý khuyến mãi",
      "icon"  => "fas fa-ticket-alt",
      "link"  => "admin/coupon",
      "roles" => [0, 1]
    ],
// Nhân viên //tí sửa
    [
      "title" => "Nhận đơn hàng",
      "icon"  => "fas fa-receipt",
      "link"  => "admin/orders",
      "roles" => [0, 3]
    ],
    [
      "title" => "Xem thực đơn",
      "icon"  => "fas fa-list",
      "link"  => "admin/POS",
      "roles" => [0, 3]
    ],

    // Quản lý cửa hàng
    [
      "title" => "Đơn hàng",
      "icon"  => "fas fa-shopping-cart",
      "link"  => "admin/m_orders",
      "roles" => [0, 2]
    ],
    [
      "title" => "Quản lý kho",
      "icon"  => "fas fa-boxes",
      "link"  => "admin/inventory",
      "roles" => [0, 2]
    ],
    [
      "title" => "Bàn",//tí sửa
      "icon"  => "fas fa-chair",
      "link"  => "admin/table",
      "roles" => [0, 2]
    ],
    
  ];
  ?>

  <nav class="nav-menu">
    <?php foreach ($menuItems as $item): ?>

      <?php if (!in_array($role, $item['roles'])) continue; ?>

      <?php
        // Tách link thành controller/action
        $parts = explode('/', trim($item['link'], '/'));

        $itemController = strtolower($parts[0] ?? '');
        $itemAction     = strtolower($parts[1] ?? 'index');

        // Kiểm tra active
        $isActive = (
            $currentController === $itemController &&
            $currentAction     === $itemAction
        );
      ?>

      <a href="https://caffeshop.hieuthuocyentam.id.vn/<?= $item['link'] ?>"
         class="nav-item <?= $isActive ? 'active' : '' ?>">
        <i class="<?= $item['icon'] ?>"></i>
        <span><?= $item['title'] ?></span>
      </a>

    <?php endforeach; ?>
  </nav>
</aside>
