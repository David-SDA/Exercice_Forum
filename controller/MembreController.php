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
            $idMembre = filter_input(INPUT_GET, "idMembre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /* Si le filtrage fonctionne */
            if($idMembre){
                return [
                    "view" => VIEW_DIR . "security/profilAdmin.php",
                    "data" => [
                        "membre" => $membreManager->findOneById($idMembre),
                        "nombreTopics" => $membreManager->nombreTopicsDeMembre($idMembre),
                        "nombrePosts" => $membreManager->nombrePostsDeMembre($idMembre),
                        "topics" => $topicManager->trouverTopicsMembre($idMembre, ["dateCreation", "DESC"]),
                        "derniersPosts" => $postManager->trouverCinqDernierPost($idMembre)
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