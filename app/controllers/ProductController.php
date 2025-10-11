<?php
class ProductController extends Controller {
    public function detail($id = 0) {
        $productModel = $this->model('Product');
        $product = $productModel->getProductById($id);

        $this->view('product/detail', ['product' => $product]);
    }
}
?>