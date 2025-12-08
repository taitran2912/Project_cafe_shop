<?php
class ThankyouController extends Controller {
    public function index() {
        // $branch = $branchModel->getAllBranch();


        $data = [
            'title' => 'Cảm ơn'
        ];
        $this->view('thankyou/thankyou', $data);
    }
}
