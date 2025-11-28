<header class="top-bar flex items-center justify-between px-4 py-3 bg-white shadow">
    <!-- Menu Toggle -->
    <button class="btn-menu-toggle text-gray-700 text-xl">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Page Title -->
    <h1 class="page-title text-lg font-semibold">
        <?= $data['title'] ?>
    </h1>

    <!-- Actions -->
     <div class="top-bar-actions relative">
        <a href="https://caffeshop.hieuthuocyentam.id.vn/logout"class="block px-4 py-2 mt-2 text-gray-700 hover:bg-gray-100 transition rounded-lg">
            <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
        </a>
    </div>
    <div class="top-bar-actions relative">
        <button class="btn-notification text-gray-700 text-xl relative">
            <i class="fas fa-bell"></i>
            <span class="badge absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 rounded-full">3</span>
        </button>
    </div>
</header>


