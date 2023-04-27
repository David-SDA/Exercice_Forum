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

        public function ajouterPost(){
            $postManager = new PostManager();

            $contenu = filter_input(INPUT_POST, "contenu", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $postManager->add([
                "contenu" => $contenu,
                "membre_id" => 2, // membre fixe
                "topic_id" => $id
            ]);

            return [
                "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                "data" => [
                    "posts" => $postManager->trouverPostsDansTopic($id)
                ]
            ];
        }
    }