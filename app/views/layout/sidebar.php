<style>
  /* Sidebar */
.sidebar {
    width: 260px;
    background: linear-gradient(180deg, #5d4037 0%, #4e342e 100%);
    color: #f5f1ed;
    padding: 24px 0;
    box-shadow: 4px 0 12px rgba(0, 0, 0, 0.15);
    position: fixed;
    height: 100vh;
    overflow-y: auto;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 24px 24px;
    border-bottom: 1px solid rgba(245, 241, 237, 0.2);
    margin-bottom: 24px;
}

.logo i {
    font-size: 32px;
    color: #d7a86e;
}

.logo span {
    font-size: 20px;
    font-weight: 700;
    color: #f5f1ed;
}

.nav-menu {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 0 12px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    color: #e0d5c7;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 15px;
}

.nav-item:hover {
    background-color: rgba(215, 168, 110, 0.15);
    color: #f5f1ed;
}

.nav-item.active {
    background-color: #d7a86e;
    color: #3d2817;
    font-weight: 600;
}

.nav-item i {
    font-size: 18px;
    width: 20px;
}
.main-content {
    margin-left: 260px; /* width of the sidebar */
    transition: margin-left 0.2s ease;
}

@media (max-width: 768px) {
    /* On small screens sidebar becomes horizontal/stacked; remove left margin */
    .main-content {
        margin-left: 0;
    }
    .sidebar {
        position: relative;
        height: auto;
    }
}
</style>

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
      "link"  => "admin/manager/QLTD",
      "roles" => [1]
    ],
    [
      "title" => "Quản lý chi nhánh",
      "icon"  => "fas fa-store",
      "link"  => "admin/manager/QLCN",
      "roles" => [1]
    ],
    [
      "title" => "Thống kê báo cáo",
      "icon"  => "fas fa-chart-bar",
      "link"  => "admin/manager/TKBC",
      "roles" => [1]
    ],
    [
      "title" => "Quản lý nhân viên",
      "icon"  => "fas fa-user-shield",
      "link"  => "admin/manager/PQNV",
      "roles" => [1]
    ],
    [
      "title" => "Quản lý khuyến mãi",
      "icon"  => "fas fa-ticket-alt",
      "link"  => "admin/manager/QLKM",
      "roles" => [1]
    ]
  ];
  ?>

  <nav class="nav-menu">
    <?php foreach ($menuItems as $item): ?>
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
      
    <?php endforeach; ?>
  </nav>

  <!-- <div class="sidebar-footer">
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
  </div> -->
</aside>
