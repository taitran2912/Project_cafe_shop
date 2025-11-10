<?php
/**
 * AJAX Search API for Employee Management
 * Returns JSON response with paginated account search results
 */

header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../../../../controllers/AccountController.php';

try {
    $accountController = new AccountController();
    
    // Sanitize and validate inputs
    $q = isset($_GET['q']) ? trim($_GET['q']) : null;
    $page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
    $limit = max(1, min(100, isset($_GET['limit']) ? (int)$_GET['limit'] : 5)); // Cap at 100
    
    $pagination = $accountController->paginate($page, $limit, $q);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'accounts' => $pagination['accounts'],
        'totalItems' => $pagination['totalItems'],
        'totalPages' => $pagination['totalPages'],
        'currentPage' => $pagination['currentPage'],
        'limit' => $pagination['limit']
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Có lỗi xảy ra khi tìm kiếm'
    ], JSON_UNESCAPED_UNICODE);
}
