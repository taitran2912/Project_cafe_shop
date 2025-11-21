<?php
  if(!isset($_SESSION))
    session_start();
  $role = $_SESSION['user']['Role'];
  $userID = $_SESSION['user']['ID'];
  $name = $_SESSION['user']['Name'];

  $uri = $_SERVER['REQUEST_URI'];
  $base = '/Project_cafe_shop/admin/';
  // Remove query string to get clean action
  $uriWithoutQuery = strtok($uri, '?');
  $action = trim(str_replace($base, '', $uriWithoutQuery), '/');
  
  // Set default action to menu if empty
  if (empty($action)) {
    $action = 'menu';
  }

  // Load models based on action - BEFORE any HTML output
  if ($action === 'menu') {
    require_once 'app/models/Product.php';
  } elseif ($action === 'user') {
    require_once 'app/models/Account.php';
  } elseif ($action === 'coupon') {
    require_once 'app/models/Coupon.php';
  } elseif ($action === 'branch') {
    require_once 'app/models/Branch.php';
  }

  
  // ========== USER MANAGEMENT ==========
  if ($action === 'user') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_account') {
        $accountData = [
            'Name' => trim($_POST['name'] ?? ''),
            'Password' => password_hash(trim($_POST['password'] ?? ''), PASSWORD_DEFAULT),
            'Phone' => trim($_POST['phone'] ?? ''),
            'Role' => (int)($_POST['role'] ?? 2),
            'Status' => trim($_POST['status'] ?? 'active')
        ];
        if (!empty($accountData['Name']) && !empty($_POST['password']) && !empty($accountData['Phone'])) {
            $accountModel = new Account();
            if ($accountModel->createAccount($accountData)) {
                header('Location: /Project_cafe_shop/admin/user?success=add');
                exit;
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_account') {
        $accountId = (int)($_POST['account_id'] ?? 0);
        if ($accountId > 0) {
            $accountData = [
                'Name' => trim($_POST['name'] ?? ''),
                'Phone' => trim($_POST['phone'] ?? ''),
                'Role' => (int)($_POST['role'] ?? 2),
                'Status' => trim($_POST['status'] ?? 'active')
            ];
            if (!empty($_POST['password'])) {
                $accountData['Password'] = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
            }
            if (!empty($accountData['Name']) && !empty($accountData['Phone'])) {
                $accountModel = new Account();
                if ($accountModel->updateAccount($accountId, $accountData)) {
                    header('Location: /Project_cafe_shop/admin/user?success=edit');
                    exit;
                }
            }
        }
    }
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $accountId = (int)$_GET['id'];
        if ($accountId > 0) {
            $accountModel = new Account();
            if ($accountModel->deleteAccount($accountId)) {
                header('Location: /Project_cafe_shop/admin/user?success=delete');
                exit;
            }
        }
    }
  }

  // ========== PRODUCT MANAGEMENT ==========
  if ($action === 'menu') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
        $productData = [
            'ID_category' => (int)($_POST['category'] ?? 0),
            'Name' => trim($_POST['name'] ?? ''),
            'Description' => trim($_POST['description'] ?? ''),
            'Price' => (float)($_POST['price'] ?? 0),
            'Status' => trim($_POST['status'] ?? 'available'),
            'Image' => ''
        ];
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/image/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid('product_') . '.' . $fileExtension;
                $uploadPath = $uploadDir . $newFileName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $productData['Image'] = 'public/image/products/' . $newFileName;
                }
            }
        }
        
        if (!empty($productData['Name']) && $productData['ID_category'] > 0 && $productData['Price'] > 0) {
            $productModel = new Product();
            if ($productModel->createProduct($productData)) {
                header('Location: /Project_cafe_shop/admin/menu?success=add');
                exit;
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_product') {
        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            $productData = [
                'ID_category' => (int)($_POST['category'] ?? 0),
                'Name' => trim($_POST['name'] ?? ''),
                'Description' => trim($_POST['description'] ?? ''),
                'Price' => (float)($_POST['price'] ?? 0),
                'Status' => trim($_POST['status'] ?? 'available')
            ];
            
            // Handle image upload for edit
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../../public/image/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = uniqid('product_') . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $productData['Image'] = 'public/image/products/' . $newFileName;
                    }
                }
            }
            
            if (!empty($productData['Name']) && $productData['Price'] > 0) {
                $productModel = new Product();
                if ($productModel->updateProduct($productId, $productData)) {
                    header('Location: /Project_cafe_shop/admin/menu?success=edit');
                    exit;
                }
            }
        }
    }
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $productId = (int)$_GET['id'];
        if ($productId > 0) {
            $productModel = new Product();
            if ($productModel->deleteProduct($productId)) {
                header('Location: /Project_cafe_shop/admin/menu?success=delete');
                exit;
            }
        }
    }
  }

  // ========== COUPON MANAGEMENT ==========
  if ($action === 'coupon') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_coupon') {
        $couponData = [
            'Code' => trim($_POST['code'] ?? ''),
            'Description' => trim($_POST['description'] ?? ''),
            'Discount_value' => (float)($_POST['discount_value'] ?? 0),
            'Start' => trim($_POST['start'] ?? ''),
            'End' => trim($_POST['end'] ?? ''),
            'Status' => trim($_POST['status'] ?? 'active'),
            'Quantity' => (int)($_POST['quantity'] ?? 0)
        ];
        if (!empty($couponData['Code']) && $couponData['Discount_value'] > 0) {
            $couponModel = new Coupon();
            if ($couponModel->createCoupon($couponData)) {
                header('Location: /Project_cafe_shop/admin/coupon?success=add');
                exit;
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_coupon') {
        $couponId = (int)($_POST['coupon_id'] ?? 0);
        if ($couponId > 0) {
            $couponData = [
                'Code' => trim($_POST['code'] ?? ''),
                'Description' => trim($_POST['description'] ?? ''),
                'Discount_value' => (float)($_POST['discount_value'] ?? 0),
                'Start' => trim($_POST['start'] ?? ''),
                'End' => trim($_POST['end'] ?? ''),
                'Status' => trim($_POST['status'] ?? 'active'),
                'Quantity' => (int)($_POST['quantity'] ?? 0)
            ];
            if (!empty($couponData['Code']) && $couponData['Discount_value'] > 0) {
                $couponModel = new Coupon();
                if ($couponModel->updateCoupon($couponId, $couponData)) {
                    header('Location: /Project_cafe_shop/admin/coupon?success=edit');
                    exit;
                }
            }
        }
    }
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $couponId = (int)$_GET['id'];
        if ($couponId > 0) {
            $couponModel = new Coupon();
            if ($couponModel->deleteCoupon($couponId)) {
                header('Location: /Project_cafe_shop/admin/coupon?success=delete');
                exit;
            }
        }
    }
  }

  // ========== BRANCH MANAGEMENT ==========
  if ($action === 'branch') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_branch') {
        $branchData = [
            'Name' => trim($_POST['name'] ?? ''),
            'Address' => trim($_POST['address'] ?? ''),
            'Phone' => trim($_POST['phone'] ?? ''),
            'Status' => trim($_POST['status'] ?? 'active')
        ];
        if (!empty($branchData['Name']) && !empty($branchData['Address']) && !empty($branchData['Phone'])) {
            $branchModel = new Branch();
            if ($branchModel->createBranch($branchData)) {
                header('Location: /Project_cafe_shop/admin/branch?success=add');
                exit;
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_branch') {
        $branchId = (int)($_POST['branch_id'] ?? 0);
        if ($branchId > 0) {
            $branchData = [
                'Name' => trim($_POST['name'] ?? ''),
                'Address' => trim($_POST['address'] ?? ''),
                'Phone' => trim($_POST['phone'] ?? ''),
                'Status' => trim($_POST['status'] ?? 'active')
            ];
            if (!empty($branchData['Name']) && !empty($branchData['Address']) && !empty($branchData['Phone'])) {
                $branchModel = new Branch();
                if ($branchModel->updateBranch($branchId, $branchData)) {
                    header('Location: /Project_cafe_shop/admin/branch?success=edit');
                    exit;
                }
            }
        }
    }
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $branchId = (int)$_GET['id'];
        if ($branchId > 0) {
            $branchModel = new Branch();
            if ($branchModel->deleteBranch($branchId)) {
                header('Location: /Project_cafe_shop/admin/branch?success=delete');
                exit;
            }
        }
    }
  }
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