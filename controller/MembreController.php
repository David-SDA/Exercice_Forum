<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\MembreManager;
    use Model\Managers\TopicManager;

    class MembreController extends AbstractController implements ControllerInterface{
        
        public function index(){}

        /**
         * Permet d'accéder au profil d'un membre par un admin
         */
        public function profilAdmin(){
            $membreManager = new MembreManager();
            $topicManager = new TopicManager();

            /* On filtre l'input */
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if($id){
                return [
                    "view" => VIEW_DIR . "security/profilAdmin.php",
                    "data" => [
                        "membre" => $membreManager->findOneById($id),
                        "nombreTopics" => $membreManager->nombreTopicsDeMembre($id),
                        "nombrePosts" => $membreManager->nombrePostsDeMembre($id),
                        "topics" => $topicManager->trouverTopicsMembre($id, ["dateCreation", "DESC"])
                    ]
                ];
            }
        }
    }