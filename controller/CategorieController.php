<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\CategorieManager;

    class CategorieController extends AbstractController implements ControllerInterface{
        
        public function index(){
            $categorieManager = new CategorieManager();

            return [
                "view" => VIEW_DIR."forum/Categorie/listerCategories.php",
                "data" => [
                    "categories" => $categorieManager->findAll(["id_categorie", "ASC"])
                ]
            ];
        }

        public function allerPageAjoutCategorie(){
            return [
                "view" => VIEW_DIR . "forum/Categorie/ajouterCategorie.php",
                "data" => [""]
            ];
        }
    }