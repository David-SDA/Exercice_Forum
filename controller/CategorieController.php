<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\CategorieManager;
    use Model\Managers\TopicManager;
    use Model\Managers\PostManager;

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

            return [
                "view" => VIEW_DIR."forum/Categorie/listerCategories.php",
                "data" => [
                    "categories" => $categorieManager->findAll(["id_categorie", "ASC"])
                ]
            ];
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

        /**
         * Permet de supprimer une catégorie
         */
        public function supprimerCategorie(){
            $session = new Session();
            $categorieManager = new CategorieManager();
            $topicManager = new TopicManager();
            $postManager = new PostManager();

            /* On filtre l'input */
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if($id){
                if($session->isAdmin()){
                    if($postManager->supprimerPostsDeTopicsDansCategorie($id) && $topicManager->supprimerTopicDeCategorie($id) && $categorieManager->delete($id)){
                        $session->addFlash("success", "Suppression réussi !");
                        return [
                            "view" => VIEW_DIR."forum/Categorie/listerCategories.php",
                            "data" => [
                                "categories" => $categorieManager->findAll(["id_categorie", "ASC"])
                            ]
                        ];
                    }
                    else{
                        $session->addFlash("error", "Échec de la suppression !");
                        return [
                            "view" => VIEW_DIR."forum/Categorie/listerCategories.php",
                            "data" => [
                                "categories" => $categorieManager->findAll(["id_categorie", "ASC"])
                            ]
                        ];
                    }
                }
                else{
                    $session->addFlash("error", "Échec de la suppression !");
                    return [
                        "view" => VIEW_DIR."forum/Categorie/listerCategories.php",
                        "data" => [
                            "categories" => $categorieManager->findAll(["id_categorie", "ASC"])
                        ]
                    ];
                }
            }
            else{
                $session->addFlash("error", "Échec de la suppression !");
                return [
                    "view" => VIEW_DIR."forum/Categorie/listerCategories.php",
                    "data" => [
                        "categories" => $categorieManager->findAll(["id_categorie", "ASC"])
                    ]
                ];
            }
        }
    }