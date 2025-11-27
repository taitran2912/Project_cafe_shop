<?php
//   if(!isset($_SESSION))
//     session_start();
//   $role = $_SESSION['user']['Role'];
//   $userID = $_SESSION['user']['ID'];
//   $name = $_SESSION['user']['Name'];

<<<<<<< HEAD
// $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// // Tìm vị trí "/admin/"
// $pos = strpos($uriPath, '/admin/');

// // Lấy chuỗi sau "/admin/"
// $action = $pos !== false ? substr($uriPath, $pos + strlen('/admin/')) : '';

// $action = trim($action, '/');

// // Nếu không có action → mặc định là menu
// if ($action === '') {
//     $action = 'menu';
// }
=======
  $uri = $_SERVER['REQUEST_URI'];
  $base = '/admin/';
  // Remove query string to get clean action
  $uriWithoutQuery = strtok($uri, '?');
  $action = trim(str_replace($base, '', $uriWithoutQuery), '/');
>>>>>>> dc05dd19bb54e8b6f22efab29ff5412e98518b19
  
//   // Set default action to menu if empty
//   if (empty($action)) {
//     $action = 'menu';
//   }

//   // Load models based on action - BEFORE any HTML output
//   if ($action === 'menu') {
//     require_once 'app/models/Product.php';
//   } elseif ($action === 'user') {
//     require_once 'app/models/Account.php';
//   } elseif ($action === 'coupon') {
//     require_once 'app/models/Coupon.php';
//   } elseif ($action === 'branch') {
//     require_once 'app/models/Branch.php';
//   } elseif ($action === 'warehouse') {
//     require_once 'app/models/Inventory.php';
//     require_once 'app/models/Material.php';
//   } elseif ($action === 'orders') {
//     require_once 'app/models/Order.php';
//   }

  
//   // ========== ORDERS MANAGEMENT ==========
//   if ($action === 'orders') {
//     // Handle AJAX request for pending count
//     if (isset($_GET['action']) && $_GET['action'] === 'get_pending_count') {
//         $orderModel = new Order();
//         $count = $orderModel->getPendingCount();
//         header('Content-Type: application/json');
//         echo json_encode(['count' => $count]);
//         exit;
//     }

//     // Handle confirm order
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'confirm_order') {
//         $orderId = (int)($_POST['order_id'] ?? 0);
//         if ($orderId > 0) {
//             $orderModel = new Order();
//             if ($orderModel->confirmOrder($orderId)) {
//                 header('Location: /Project_cafe_shop/admin/orders?success=confirm');
//                 exit;
//             }
//         }
//         header('Location: /Project_cafe_shop/admin/orders?error=confirm');
//         exit;
//     }

//     // Handle AJAX update item status
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_item_status') {
//         $itemId = (int)($_POST['item_id'] ?? 0);
//         $status = $_POST['status'] ?? '';
        
//         $validStatuses = ['pending', 'preparing', 'completed', 'served'];
//         if ($itemId > 0 && in_array($status, $validStatuses)) {
//             // For now, just return success (item status tracking can be added to DB later)
//             header('Content-Type: application/json');
//             echo json_encode(['success' => true]);
//             exit;
//         }
        
//         header('Content-Type: application/json');
//         echo json_encode(['success' => false]);
//         exit;
//     }
//   }
  
//   // ========== USER MANAGEMENT ==========
//   if ($action === 'user') {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_account') {
//         $accountData = [
//             'Name' => trim($_POST['name'] ?? ''),
//             'Password' => password_hash(trim($_POST['password'] ?? ''), PASSWORD_DEFAULT),
//             'Phone' => trim($_POST['phone'] ?? ''),
//             'Role' => (int)($_POST['role'] ?? 2),
//             'Status' => trim($_POST['status'] ?? 'active')
//         ];
//         if (!empty($accountData['Name']) && !empty($_POST['password']) && !empty($accountData['Phone'])) {
//             $accountModel = new Account();
//             if ($accountModel->createAccount($accountData)) {
//                 header('Location: /Project_cafe_shop/admin/user?success=add');
//                 exit;
//             }
//         }
//     }
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_account') {
//         $accountId = (int)($_POST['account_id'] ?? 0);
//         if ($accountId > 0) {
//             $accountData = [
//                 'Name' => trim($_POST['name'] ?? ''),
//                 'Phone' => trim($_POST['phone'] ?? ''),
//                 'Role' => (int)($_POST['role'] ?? 2),
//                 'Status' => trim($_POST['status'] ?? 'active')
//             ];
//             if (!empty($_POST['password'])) {
//                 $accountData['Password'] = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
//             }
//             if (!empty($accountData['Name']) && !empty($accountData['Phone'])) {
//                 $accountModel = new Account();
//                 if ($accountModel->updateAccount($accountId, $accountData)) {
//                     header('Location: /Project_cafe_shop/admin/user?success=edit');
//                     exit;
//                 }
//             }
//         }
//     }
//     if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
//         $accountId = (int)$_GET['id'];
//         if ($accountId > 0) {
//             $accountModel = new Account();
//             if ($accountModel->deleteAccount($accountId)) {
//                 header('Location: /Project_cafe_shop/admin/user?success=delete');
//                 exit;
//             }
//         }
//     }
//   }

//   // ========== PRODUCT MANAGEMENT ==========
//   if ($action === 'menu') {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
//         $productData = [
//             'ID_category' => (int)($_POST['category'] ?? 0),
//             'Name' => trim($_POST['name'] ?? ''),
//             'Description' => trim($_POST['description'] ?? ''),
//             'Price' => (float)($_POST['price'] ?? 0),
//             'Status' => trim($_POST['status'] ?? 'available'),
//             'Image' => ''
//         ];
        
//         // Handle image upload
//         if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
//             $uploadDir = __DIR__ . '/../../../public/image/products/';
//             if (!is_dir($uploadDir)) {
//                 mkdir($uploadDir, 0777, true);
//             }
//             $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
//             $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
//             if (in_array($fileExtension, $allowedExtensions)) {
//                 $newFileName = uniqid('product_') . '.' . $fileExtension;
//                 $uploadPath = $uploadDir . $newFileName;
//                 if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
//                     $productData['Image'] = 'public/image/products/' . $newFileName;
//                 }
//             }
//         }
        
//         if (!empty($productData['Name']) && $productData['ID_category'] > 0 && $productData['Price'] > 0) {
//             $productModel = new Product();
//             if ($productModel->createProduct($productData)) {
//                 header('Location: /Project_cafe_shop/admin/menu?success=add');
//                 exit;
//             }
//         }
//     }
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_product') {
//         $productId = (int)($_POST['product_id'] ?? 0);
//         if ($productId > 0) {
//             $productData = [
//                 'ID_category' => (int)($_POST['category'] ?? 0),
//                 'Name' => trim($_POST['name'] ?? ''),
//                 'Description' => trim($_POST['description'] ?? ''),
//                 'Price' => (float)($_POST['price'] ?? 0),
//                 'Status' => trim($_POST['status'] ?? 'available')
//             ];
            
//             // Handle image upload for edit
//             if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
//                 $uploadDir = __DIR__ . '/../../../public/image/products/';
//                 if (!is_dir($uploadDir)) {
//                     mkdir($uploadDir, 0777, true);
//                 }
//                 $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
//                 $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
//                 if (in_array($fileExtension, $allowedExtensions)) {
//                     $newFileName = uniqid('product_') . '.' . $fileExtension;
//                     $uploadPath = $uploadDir . $newFileName;
//                     if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
//                         $productData['Image'] = 'public/image/products/' . $newFileName;
//                     }
//                 }
//             }
            
//             if (!empty($productData['Name']) && $productData['Price'] > 0) {
//                 $productModel = new Product();
//                 if ($productModel->updateProduct($productId, $productData)) {
//                     header('Location: /Project_cafe_shop/admin/menu?success=edit');
//                     exit;
//                 }
//             }
//         }
//     }
//     if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
//         $productId = (int)$_GET['id'];
//         if ($productId > 0) {
//             $productModel = new Product();
//             if ($productModel->deleteProduct($productId)) {
//                 header('Location: /Project_cafe_shop/admin/menu?success=delete');
//                 exit;
//             }
//         }
//     }
//   }

//   // ========== COUPON MANAGEMENT ==========
//   if ($action === 'coupon') {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_coupon') {
//         $couponData = [
//             'Code' => trim($_POST['code'] ?? ''),
//             'Description' => trim($_POST['description'] ?? ''),
//             'Discount_value' => (float)($_POST['discount_value'] ?? 0),
//             'Start' => trim($_POST['start'] ?? ''),
//             'End' => trim($_POST['end'] ?? ''),
//             'Status' => trim($_POST['status'] ?? 'active'),
//             'Quantity' => (int)($_POST['quantity'] ?? 0)
//         ];
//         if (!empty($couponData['Code']) && $couponData['Discount_value'] > 0) {
//             $couponModel = new Coupon();
//             if ($couponModel->createCoupon($couponData)) {
//                 header('Location: /Project_cafe_shop/admin/coupon?success=add');
//                 exit;
//             }
//         }
//     }
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_coupon') {
//         $couponId = (int)($_POST['coupon_id'] ?? 0);
//         if ($couponId > 0) {
//             $couponData = [
//                 'Code' => trim($_POST['code'] ?? ''),
//                 'Description' => trim($_POST['description'] ?? ''),
//                 'Discount_value' => (float)($_POST['discount_value'] ?? 0),
//                 'Start' => trim($_POST['start'] ?? ''),
//                 'End' => trim($_POST['end'] ?? ''),
//                 'Status' => trim($_POST['status'] ?? 'active'),
//                 'Quantity' => (int)($_POST['quantity'] ?? 0)
//             ];
//             if (!empty($couponData['Code']) && $couponData['Discount_value'] > 0) {
//                 $couponModel = new Coupon();
//                 if ($couponModel->updateCoupon($couponId, $couponData)) {
//                     header('Location: /Project_cafe_shop/admin/coupon?success=edit');
//                     exit;
//                 }
//             }
//         }
//     }
//     if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
//         $couponId = (int)$_GET['id'];
//         if ($couponId > 0) {
//             $couponModel = new Coupon();
//             if ($couponModel->deleteCoupon($couponId)) {
//                 header('Location: /Project_cafe_shop/admin/coupon?success=delete');
//                 exit;
//             }
//         }
//     }
//   }

//   // ========== BRANCH MANAGEMENT ==========
//   if ($action === 'branch') {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_branch') {
//         $branchData = [
//             'Name' => trim($_POST['name'] ?? ''),
//             'Address' => trim($_POST['address'] ?? ''),
//             'Phone' => trim($_POST['phone'] ?? ''),
//             'Status' => trim($_POST['status'] ?? 'active')
//         ];
//         if (!empty($branchData['Name']) && !empty($branchData['Address']) && !empty($branchData['Phone'])) {
//             $branchModel = new Branch();
//             if ($branchModel->createBranch($branchData)) {
//                 header('Location: /Project_cafe_shop/admin/branch?success=add');
//                 exit;
//             }
//         }
//     }
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_branch') {
//         $branchId = (int)($_POST['branch_id'] ?? 0);
//         if ($branchId > 0) {
//             $branchData = [
//                 'Name' => trim($_POST['name'] ?? ''),
//                 'Address' => trim($_POST['address'] ?? ''),
//                 'Phone' => trim($_POST['phone'] ?? ''),
//                 'Status' => trim($_POST['status'] ?? 'active')
//             ];
//             if (!empty($branchData['Name']) && !empty($branchData['Address']) && !empty($branchData['Phone'])) {
//                 $branchModel = new Branch();
//                 if ($branchModel->updateBranch($branchId, $branchData)) {
//                     header('Location: /Project_cafe_shop/admin/branch?success=edit');
//                     exit;
//                 }
//             }
//         }
//     }
//     if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
//         $branchId = (int)$_GET['id'];
//         if ($branchId > 0) {
//             $branchModel = new Branch();
//             if ($branchModel->deleteBranch($branchId)) {
//                 header('Location: /Project_cafe_shop/admin/branch?success=delete');
//                 exit;
//             }
//         }
//     }
//   }

//   // ==================== INVENTORY MANAGEMENT ====================
//   if ($action === 'inventory') {
//     // Add item
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_item') {
//         $id_material = (int)($_POST['id_material'] ?? 0);
//         $id_branch = (int)($_POST['id_branch'] ?? 0);
//         $quantity = (int)($_POST['quantity'] ?? 0);
        
//         if ($id_material > 0 && $id_branch > 0) {
//             $inventoryModel = new Inventory();
//             if ($inventoryModel->createInventory($id_material, $id_branch, $quantity)) {
//                 header('Location: /Project_cafe_shop/admin/inventory?success=add');
//                 exit;
//             }
//         }
//     }
//     // Edit item
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_item') {
//         $itemId = (int)($_POST['item_id'] ?? 0);
//         if ($itemId > 0) {
//             $id_material = (int)($_POST['id_material'] ?? 0);
//             $id_branch = (int)($_POST['id_branch'] ?? 0);
//             $quantity = (int)($_POST['quantity'] ?? 0);
            
//             if ($id_material > 0 && $id_branch > 0) {
//                 $inventoryModel = new Inventory();
//                 if ($inventoryModel->updateInventory($itemId, $id_material, $id_branch, $quantity)) {
//                     header('Location: /Project_cafe_shop/admin/inventory?success=edit');
//                     exit;
//                 }
//             }
//         }
//     }
//     // Delete item
//     if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
//         $itemId = (int)$_GET['id'];
//         if ($itemId > 0) {
//             $inventoryModel = new Inventory();
//             if ($inventoryModel->deleteInventory($itemId)) {
//                 header('Location: /Project_cafe_shop/admin/inventory?success=delete');
//                 exit;
//             }
//         }
//     }
//   }
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