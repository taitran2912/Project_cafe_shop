<aside class="sidebar">
  <div class="logo">
    <i class="fas fa-coffee"></i>
    <span>Cafe Manager</span>
  </div>

  <?php
  // Lấy URL hiện tại từ tham số `url` mà Router đang xử lý
  $url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
  $urlParts = explode('/', $url);
  
  // Xác định controller và action
  $currentController = isset($urlParts[0]) ? strtolower($urlParts[0]) : 'home';
  $currentAction = isset($urlParts[1]) ? strtolower($urlParts[1]) : 'index';

  $menuItems = [
    [
      "title" => "Quản lý thực đơn",
      "icon"  => "fas fa-utensils",
      "link"  => "admin/index",
      "roles" => [1]
    ],
    [
      "title" => "Quản lý chi nhánh",
      "icon"  => "fas fa-store",
      "link"  => "admin/branch",
      "roles" => [1]
    ],
    [
      "title" => "Thống kê báo cáo",
      "icon"  => "fas fa-chart-bar",
      "link"  => "admin/bao-cao",
      "roles" => [1]
    ],
    [
      "title" => "Quản lý nhân viên",
      "icon"  => "fas fa-user-shield",
      "link"  => "admin/quan-ly-nhan-vien",
      "roles" => [1]
    ],
    [
      "title" => "Quản lý khuyến mãi",
      "icon"  => "fas fa-ticket-alt",
      "link"  => "admin/khuyen-mai",
      "roles" => [1]
    ]
  ];
  ?>

  <nav class="nav-menu">
    <?php foreach ($menuItems as $item): ?>
      <?php if (in_array($role, $item['roles'])): ?>
        <?php
          // Tách controller/action từ link menu
          $parts = explode('/', $item['link']);
          $itemController = strtolower($parts[0] ?? '');
          $itemAction = strtolower($parts[1] ?? 'index');

          // So sánh controller/action hiện tại với link menu
          $isActive = ($currentController === $itemController && $currentAction === $itemAction);
        ?>
        <a href="/Project_cafe_shop/<?= $item['link'] ?>"
           class="nav-item <?= $isActive ? 'active' : '' ?>">
          <i class="<?= $item['icon'] ?>"></i>
          <span><?= $item['title'] ?></span>
        </a>
      <?php endif; ?>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-footer">
    <div class="user-info">
      <i class="fas fa-user-circle"></i>
      <div>
        <div class="user-name"><?= $name ?></div>
        <div class="user-role"><?= $roleName ?></div>
      </div>
    </div>
    <button class="btn-logout">
      <i class="fas fa-sign-out-alt"></i>
    </button>
  </div>
</aside>
