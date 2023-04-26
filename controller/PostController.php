<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\PostManager;

    class PostController extends AbstractController implements ControllerInterface{
        
        public function index(){}

        public function listerPostsDansTopic(){
            $postManager = new PostManager();

            return [
                "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                "data" => [
                    "posts" => $postManager->trouverPostsDansTopic($_GET["id"])
                ]
            ];
        }
    }