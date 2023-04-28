<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\CategorieManager;

    class CategorieController extends AbstractController implements ControllerInterface{
        
        /**
         * Permet de lister les catégories
         */
        public function index(){
            $categorieManager = new CategorieManager();

            return [
                "view" => VIEW_DIR."forum/Categorie/listerCategories.php",
                "data" => [
                    "categories" => $categorieManager->findAll(["id_categorie", "ASC"])
                ]
            ];
        }

        /**
         * Permet d'aller à la page d'ajout d'une catégorie
         */
        public function allerPageAjoutCategorie(){
            return ["view" => VIEW_DIR . "forum/Categorie/ajouterCategorie.php"];
        }

        /**
         * Permet d'ajouter une catégorie et de créer un message de confirmation
         */
        public function ajouterCategorie(){
            $categorieManager = new CategorieManager();
            
            $nomCategorie = filter_input(INPUT_POST, "nomCategorie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $sessionManager = new Session();
            if($categorieManager->add(["nomCategorie" => $nomCategorie])){
                $sessionManager->addFlash("success", "Ajout réussi !");
            }
            else{
                $sessionManager->addFlash("error", "Echec de l'ajout !");
            }

            return["view" => VIEW_DIR . "forum/Categorie/ajouterCategorie.php"];
        }

        /**
         * Permet d'aller à la page d'ajout d'un topic
         */
        public function allerPageAjoutTopic(){
            $categorieManager = new CategorieManager();
            return [
                "view" => VIEW_DIR . "forum/Topic/ajouterTopic.php",
                "data" => [
                    "categories" => $categorieManager->findAll()
                ]
            ];
        }
    }