<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\CategorieManager;
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
                    "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"]) // On cherche tout les topic trier du plus récent au plus ancien
                ]
            ];
        }

        /**
         * Permet de lister les topics d'une categorie
         */
        public function listerTopicsDansCategorie(){
            $topicManager = new TopicManager();
            $categorieManager = new CategorieManager();

            /* On filtre l'input */
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            return [
                "view" => VIEW_DIR . "forum/Topic/listerTopicsDansCategorie.php",
                "data" => [
                    "topics" => $topicManager->trouverTopicsParCategorie($id, ["dateCreation", "DESC"]),
                    "categorie" => $categorieManager->findOneById($id)
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
                    "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"])
                ]
            ];
        }

        /**
         * Permet de supprimer un topic avec ses posts inclues
         */
        public function supprimerTopic(){
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* On filtre l'input */
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            /* Si le filtrage fonctionne */
            if($id){
                if($session->getUser()->getId() == $topicManager->idDuMembreDuTopic($id) || $session->isAdmin()){ // on vérifie que le membre actuelle est bien celui qui supprime le topic ou que c'est l'admin
                    if($postManager->supprimerPostsDuTopic($id) && $topicManager->delete($id)){ // On supprime les posts du topic puis le topic
                        $session->addFlash("success", "Suppression réussi !");
                    }
                    else{
                        $session->addFlash("error", "Echec de la suppression !");
                    }
                }
            }
            else{
                $session->addFlash("error", "Echec de la suppression !!");
            }

            return [
                "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                "data" =>[
                    "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"])
                ]
            ];
        }

        /**
         * Permet de verrouiller un topic
         */
        public function verrouillerTopic(){
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* On filtre l'input */
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if($id){
                if($session->getUser()->getId() == $topicManager->idDuMembreDuTopic($id) || $session->isAdmin()){ // on vérifie que le membre actuelle est bien celui qui supprime le topic ou que c'est l'admin
                    if($topicManager->verrouillerTopic($id)){
                        $session->addFlash("success", "Verrouillage réussi !");
                    }
                    else{
                        $session->addFlash("error", "Echec du verrouillage !");
                    }
                }
            }
            else{
                $session->addFlash("error", "Echec du verrouillage !");
            }
            return [
                "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                "data" => [
                    "posts" => $postManager->trouverPostsDansTopic($id),
                    "ancien" => $postManager->trouverPlusAncienPost($id),
                    "topic" => $topicManager->findOneById($id)
                ]
            ];
        }

        /**
         * Permet de verrouiller un topic
         */
        public function deverrouillerTopic(){
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* On filtre l'input */
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if($id){
                if($session->getUser()->getId() == $topicManager->idDuMembreDuTopic($id) || $session->isAdmin()){ // on vérifie que le membre actuelle est bien celui qui supprime le topic ou que c'est l'admin
                    if($topicManager->deverrouillerTopic($id)){
                        $session->addFlash("success", "Déverrouillage réussi !");
                    }
                    else{
                        $session->addFlash("error", "Echec du Déverrouillage !");
                    }
                }
            }
            else{
                $session->addFlash("error", "Echec du Déverrouillage !");
            }
            return [
                "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                "data" => [
                    "posts" => $postManager->trouverPostsDansTopic($id),
                    "ancien" => $postManager->trouverPlusAncienPost($id),
                    "topic" => $topicManager->findOneById($id)
                ]
            ];
        }
    }