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
            /* On utilise les managers nécessaires */
            $categorieManager = new CategorieManager();
            return [
                "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                "data" => [
                    "categories" => $categorieManager->trouverCategorieAvecNombreTopic(["nomCategorie", "ASC"])
                ]
            ];
        }

        /**
         * Permet d'aller à la page d'ajout d'une catégorie
         */
        public function allerPageAjoutCategorie(){
            return [
                "view" => VIEW_DIR . "forum/Categorie/ajouterCategorie.php"
            ];
        }

        /**
         * Permet d'ajouter une catégorie
         */
        public function ajouterCategorie(){
            /* On utilise les managers nécessaires */
            $categorieManager = new CategorieManager();
            $session = new Session();

            if(isset($_POST["submitCategorie"])){

                /* On filtre l'input */
                $nomCategorie = filter_input(INPUT_POST, "nomCategorie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                /* Si le filtrage fonctionne */
                if($nomCategorie){
                    
                    /* Si c'est bien l'admin qui veut ajouter une catégorie */
                    if($session->isAdmin()){

                        /* On ajoute une catégorie */
                        if($categorieManager->add(["nomCategorie" => $nomCategorie])){
                            $session->addFlash("success", "Ajout de la catégorie '$nomCategorie' réussi !");
                            return [
                                "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                                "data" => [
                                    "categories" => $categorieManager->trouverCategorieAvecNombreTopic(["nomCategorie", "ASC"])
                                ]
                            ];
                        }
                        else{
                            $session->addFlash("error", "Échec de l'ajout de la catégorie !");
                            return [
                                "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                                "data" => [
                                    "categories" => $categorieManager->trouverCategorieAvecNombreTopic(["nomCategorie", "ASC"])
                                ]
                            ];
                        }
                    }
                    else{
                        $session->addFlash("error", "Échec de l'ajout de la catégorie !");
                        return [
                            "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                            "data" => [
                                "categories" => $categorieManager->trouverCategorieAvecNombreTopic(["nomCategorie", "ASC"])
                            ]
                        ];
                    }
                }
                else{
                    $session->addFlash("error", "Échec de l'ajout de la catégorie !");
                    return [
                        "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                        "data" => [
                            "categories" => $categorieManager->trouverCategorieAvecNombreTopic(["nomCategorie", "ASC"])
                        ]
                    ];
                }
            }
            else{
                $session->addFlash("error", "Échec de l'ajout de la catégorie !");
                return [
                    "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                    "data" => [
                        "categories" => $categorieManager->trouverCategorieAvecNombreTopic(["nomCategorie", "ASC"])
                    ]
                ];
            }
        }

        /**
         * Permet de supprimer une catégorie
         */
        public function supprimerCategorie(){
            /* On utilise les managers nécessaires */
            $categorieManager = new CategorieManager();
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* On filtre l'input */
            $idCategorie = filter_input(INPUT_GET, "idCategorie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            /* Si le filtrage fonctionne */
            if($idCategorie){

                /* Si c'est bien l'admin qui veut supprimer une catégorie */
                if($session->isAdmin()){

                    $nomCategorie = $categorieManager->findOneById($idCategorie)->getNomCategorie();

                    /* On supprime les posts des topics dans la catégorie qu'on veut supprimer, on supprime les topics de la catégorie et on supprime la catégorie */
                    if($postManager->supprimerPostsDeTopicsDansCategorie($idCategorie) && $topicManager->supprimerTopicsDeCategorie($idCategorie) && $categorieManager->delete($idCategorie)){
                        $session->addFlash("success", "Suppression de la catégorie '$nomCategorie' réussi !");
                        return [
                            "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                            "data" => [
                                "categories" => $categorieManager->trouverCategorieAvecNombreTopic(["nomCategorie", "ASC"])
                            ]
                        ];
                    }
                    else{
                        $session->addFlash("error", "Échec de la suppression !");
                        return [
                            "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                            "data" => [
                                "categories" => $categorieManager->trouverCategorieAvecNombreTopic(["nomCategorie", "ASC"])
                            ]
                        ];
                    }
                }
                else{
                    $session->addFlash("error", "Échec de la suppression !");
                    return [
                        "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                        "data" => [
                            "categories" => $categorieManager->trouverCategorieAvecNombreTopic(["nomCategorie", "ASC"])
                        ]
                    ];
                }
            }
            else{
                $session->addFlash("error", "Échec de la suppression !");
                return [
                    "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                    "data" => [
                        "categories" => $categorieManager->trouverCategorieAvecNombreTopic(["nomCategorie", "ASC"])
                    ]
                ];
            }
        }
    }