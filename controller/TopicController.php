<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\TopicManager;
    use Model\Managers\PostManager;

    class TopicController extends AbstractController implements ControllerInterface{
        
        /**
         * Permet de lister les topic
         */
        public function index(){
            $topicManager = new TopicManager();

            return [
                "view" => VIEW_DIR."forum/Topic/listerTopics.php",
                "data" => [
                    "topics" => $topicManager->findAll(["dateCreation", "DESC"]) // On cherche tout les topic trier du plus récent au plus ancien
                ]
            ];
        }

        /**
         * Permet de lister les topic d'une categorie
         */
        public function listerTopicsDansCategorie(){
            $topicManager = new TopicManager();

            /* On filtre l'input */
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
            $postManager = new PostManager();
            $sessionManager = new Session();

            /* On filtre les inputs */
            $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $categorie = filter_input(INPUT_POST, "categorie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $contenu = filter_input(INPUT_POST, "contenu", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /* Si le filtrage fonctionne */
            if($titre && $categorie && $contenu){
                $id = $topicManager->add([
                    "titre" => $titre,
                    "membre_id" => Session::getUser()->getId(),
                    "categorie_id" => $categorie
                ]); // On ajoute un topic et on récupère son id
                if($id && $postManager->add([
                    "contenu" => $contenu,
                    "membre_id" => Session::getUser()->getId(),
                    "topic_id" => $id
                ])){// On ajoute le premier post du topic
                    $sessionManager->addFlash("success", "Ajout réussi !");
                }
                else{
                    $sessionManager->addFlash("error", "Echec de l'ajout !");
                }
            }
            return [
                "view" => VIEW_DIR."forum/Topic/listerTopics.php",
                "data" => [
                    "topics" => $topicManager->findAll(["dateCreation", "DESC"])
                ]
            ];
        }

        /* Permet de supprimer un topic avec ses posts inclues */
        public function supprimerTopic(){
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* On filtre l'input */
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            /* Si le filtrage fonctionne */
            if($id){
                if($postManager->supprimerPostsDuTopic($id) && $topicManager->delete($id)){ // On supprime les posts du topic puis le topic
                    $session->addFlash("success", "Suppression réussi !");
                }
                else{
                    $session->addFlash("error", "Echec de l'ajout !");
                }
            }
            else{
                $session->addFlash("error", "Echec de l'ajout !!");
            }

            return [
                "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                "data" =>[
                    "topics" => $topicManager->findAll(["dateCreation", "DESC"])
                ]
            ];
        }
    }