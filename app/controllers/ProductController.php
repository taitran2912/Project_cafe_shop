<?php
class ProductController extends Controller {
    public function getAllProducts() {
        $productModel = $this->model('Product');
        return $productModel->getAllProducts();
    }

    public function getProductsByPage($page = 1, $limit = 5) {
        $productModel = $this->model('Product');
        return $productModel->getProductsByPage($page, $limit);
    }
    
    public function getProductCount() {
        $productModel = $this->model('Product');
        return $productModel->countProducts();
    }
    
    
    /**
     * Store a new product
     */
    public function store($data) {
        $productModel = $this->model('Product');
        $payload = [];
        $payload['ID_category'] = isset($data['ID_category']) ? (int)$data['ID_category'] : null;
        $payload['Name'] = isset($data['Name']) ? trim($data['Name']) : '';
        $payload['Description'] = isset($data['Description']) ? trim($data['Description']) : '';
        $payload['Price'] = isset($data['Price']) ? (float)$data['Price'] : 0;
        $payload['Status'] = isset($data['Status']) ? trim($data['Status']) : 'active';
        $payload['Image'] = isset($data['Image']) ? trim($data['Image']) : '';

        if ($payload['Name'] === '') return false;
        return $productModel->createProduct($payload);
    }

    /**
     * Update product
     */
    public function update($id, $data) {
        $productModel = $this->model('Product');
        $id = (int)$id;
        if ($id <= 0) return false;
        $payload = [];
        if (isset($data['ID_category'])) $payload['ID_category'] = (int)$data['ID_category'];
        if (isset($data['Name'])) $payload['Name'] = trim($data['Name']);
        if (isset($data['Description'])) $payload['Description'] = trim($data['Description']);
        if (isset($data['Price'])) $payload['Price'] = (float)$data['Price'];
        if (isset($data['Status'])) $payload['Status'] = trim($data['Status']);
        if (isset($data['Image'])) $payload['Image'] = trim($data['Image']);
        if (count($payload) === 0) return false;
        return $productModel->updateProduct($id, $payload);
    }

    /**
     * Delete product
     */
    public function delete($id) {
        $productModel = $this->model('Product');
        $id = (int)$id;
        if ($id <= 0) return false;
        return $productModel->deleteProduct($id);
    }

    /**
     * Search products with pagination for AJAX
     */
    public function search($q = '', $page = 1, $limit = 5) {
        $productModel = $this->model('Product');
        $page = (int)$page; if ($page < 1) $page = 1;
        $limit = (int)$limit; if ($limit < 1) $limit = 5;
        $totalItems = $productModel->countSearchProducts($q);
        $totalPages = ($totalItems > 0) ? (int)ceil($totalItems / $limit) : 1;
        if ($page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $limit;
        $products = $productModel->searchProducts($q, $limit, $offset);
        return [
            'products' => $products,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'limit' => $limit
        ];
    }
    
    
}

?>