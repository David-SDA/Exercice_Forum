<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\PostManager;

    class PostController extends AbstractController implements ControllerInterface{
        
        public function index(){}

        /**
         * Permet de lister les posts d'un topic
         */
        public function listerPostsDansTopic(){
            $postManager = new PostManager();

            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            return [
                "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                "data" => [
                    "posts" => $postManager->trouverPostsDansTopic($id),
                    "ancien" => $postManager->trouverPlusAncienPost($id)
                ]
            ];
        }

        /**
         * Permet d'aller à la page d'ajout d'un post
         */
        public function allerPageAjoutPost(){
            return ["view" => VIEW_DIR . "forum/Post/ajouterPost.php"];
        }

        /**
         * Permet d'ajoute un post
         */
        public function ajouterPost(){
            $postManager = new PostManager();

            $contenu = filter_input(INPUT_POST, "contenu", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $sessionManager = new Session();
            if($postManager->add([
                "contenu" => $contenu,
                "membre_id" => Session::getUser()->getId(),
                "topic_id" => $id
            ])){
                $sessionManager->addFlash("success", "Ajout réussi !");
            }
            else{
                $sessionManager->addFlash("error", "Echec de l'ajout !");
            }

            return [
                "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                "data" => [
                    "posts" => $postManager->trouverPostsDansTopic($id),
                    "ancien" => $postManager->trouverPlusAncienPost($id)
                ]
            ];
        }

        /**
         * Permet de supprimer un post
         */
        public function supprimerPost(){
            $postManager = new PostManager();
            $session = new Session();

            /* On filtre les inputs */
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $idTopic = filter_input(INPUT_GET, "idTopic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /* Si le filtrage fonctionne */
            if($id){
                if($session->getUser()->getId() == $postManager->trouverIdMembrePost($id)){
                    if($postManager->delete($id)){ // On supprime le post
                        $session->addFlash("success", "Suppression réussi !");
                    }
                    else{
                        $session->addFlash("error", "Echec de la suppression !");
                    }
                }
            }
            else{
                $session->addFlash("error", "Echec de la suppression !");
            }

            return [
                "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                "data" => [
                    "posts" => $postManager->trouverPostsDansTopic($idTopic),
                    "ancien" => $postManager->trouverPlusAncienPost($id)
                ]
            ];
        }
    }