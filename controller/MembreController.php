<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\MembreManager;
    use Model\Managers\PostManager;
    use Model\Managers\TopicManager;

    class MembreController extends AbstractController implements ControllerInterface{
        
        public function index(){}

        /**
         * Permet d'accéder au profil d'un membre par un admin
         */
        public function profilAdmin(){
            /* On utilise les managers nécessaires */
            $membreManager = new MembreManager();
            $topicManager = new TopicManager();
            $postManager = new PostManager();

            /* On filtre l'input */
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /* Si le filtrage fonctionne */
            if($id){
                return [
                    "view" => VIEW_DIR . "security/profilAdmin.php",
                    "data" => [
                        "membre" => $membreManager->findOneById($id),
                        "nombreTopics" => $membreManager->nombreTopicsDeMembre($id),
                        "nombrePosts" => $membreManager->nombrePostsDeMembre($id),
                        "topics" => $topicManager->trouverTopicsMembre($id, ["dateCreation", "DESC"]),
                        "derniersPosts" => $postManager->trouverCinqDernierPost($id)
                    ]
                ];
            }
            else{
                return [
                    "view" => VIEW_DIR . "security/listeMembres.php",
                    "data" => [
                        "membres" => $membreManager->findAll(['dateInscription', 'DESC'])
                    ]
                ];
            }
        }
    }