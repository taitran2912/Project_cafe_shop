<?php
/**
 * AJAX Search API for Product Management
 * Returns JSON response with formatted product data and pagination
 */

header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../../../../controllers/ProductController.php';

// Category mapping helper
function getCategoryNames() {
    return [
        '1' => 'Cà phê',
        '2' => 'Trà',
        '3' => 'Sinh tố',
        '4' => 'Bánh ngọt',
        '5' => 'Đồ ăn nhẹ'
    ];
}

// Format product data helper
function formatProduct($product, $categoryNames) {
    return [
        'id' => $product['ID'],
        'category' => $product['ID_category'],
        'categoryName' => $categoryNames[$product['ID_category']] ?? 'N/A',
        'name' => $product['Name'],
        'description' => $product['Description'],
        'price' => $product['Price'],
        'priceFormatted' => number_format($product['Price'], 0, ',', '.') . ' VNĐ',
        'status' => $product['Status']
    ];
}

try {
    $productController = new ProductController();
    $categoryNames = getCategoryNames();
    
    // Sanitize and validate inputs
    $searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
    $page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
    $limit = max(1, min(100, isset($_GET['limit']) ? (int)$_GET['limit'] : 5));
    
    // Get results from controller
    $result = !empty($searchQuery)
        ? $productController->search($searchQuery, $page, $limit)
        : $productController->paginate($page, $limit);
    
    // Format products for response
    $formattedProducts = array_map(
        fn($product) => formatProduct($product, $categoryNames),
        $result['products']
    );
    
    // Return success response
    echo json_encode([
        'success' => true,
        'products' => $formattedProducts,
        'pagination' => [
            'totalItems' => $result['totalItems'],
            'totalPages' => $result['totalPages'],
            'currentPage' => $result['currentPage'],
            'limit' => $result['limit']
        ]
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
