<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\TopicManager;
    use Model\Managers\PostManager; // solution viable ?

    class TopicController extends AbstractController implements ControllerInterface{
        
        /**
         * Permet de lister les topic
         */
        public function index(){
            $topicManager = new TopicManager();

            return [
                "view" => VIEW_DIR."forum/Topic/listerTopics.php",
                "data" => [
                    "topics" => $topicManager->findAll(["dateCreation", "DESC"])
                ]
            ];
        }

        /**
         * Permet de lister les topic d'une categorie
         */
        public function listerTopicsDansCategorie(){
            $topicManager = new TopicManager();
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            return [
                "view" => VIEW_DIR . "forum/Topic/listerTopicsDansCategorie.php",
                "data" => [
                    "topics" => $topicManager->trouverTopicsParCategorie($id)
                ]
            ];
        }

        /**
         * Permet d'ajouter un topic
         */
        public function ajouterTopic(){
            $topicManager = new TopicManager();
            $postManager = new PostManager(); // solution viable ?

            $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $categorie = filter_input(INPUT_POST, "categorie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $contenu = filter_input(INPUT_POST, "contenu", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $sessionManager = new Session();
            $id = $topicManager->add([
                "titre" => $titre,
                "membre_id" => 2, // membre fixe
                "categorie_id" => $categorie
            ]);
            if($id && $postManager->add([
                "contenu" => $contenu,
                "membre_id" => 2, // membre fixe
                "topic_id" => $id
            ])){
                $sessionManager->addFlash("success", "Ajout réussi !");
            }
            else{
                $sessionManager->addFlash("error", "Echec de l'ajout !");
            }
            return [
                "view" => VIEW_DIR . "home.php",
                "data" => [
                    "topics" => $topicManager->findAll(["dateCreation", "DESC"])
                ]
            ];
        }
    }