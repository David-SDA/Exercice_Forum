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
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();
            return [
                "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                "data" => [
                    "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"]) // On cherche tout les topic trier du plus récent au plus ancien
                ]
            ];
        }

        /**
         * Permet de lister les topics d'une categorie
         */
        public function listerTopicsDansCategorie(){
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();
            $categorieManager = new CategorieManager();

            /* On filtre l'input */
            $idCategorie = filter_input(INPUT_GET, "idCategorie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /* Si le filtrage fonctionne */
            if($idCategorie){
                return [
                    "view" => VIEW_DIR . "forum/Topic/listerTopicsDansCategorie.php",
                    "data" => [
                        "topics" => $topicManager->trouverTopicsParCategorie($idCategorie, ["dateCreation", "DESC"]),
                        "categorie" => $categorieManager->findOneById($idCategorie)
                    ]
                ];
            }
            else{
                return [
                    "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                    "data" => [
                        "categories" => $categorieManager->findAll()
                    ]
                ];
            }
        }

        /**
         * Permet d'aller à la page d'ajout d'un topic
         */
        public function allerPageAjoutTopic(){
            /* On utilise les managers nécessaires */
            $categorieManager = new CategorieManager();
            return [
                "view" => VIEW_DIR . "forum/Topic/ajouterTopic.php",
                "data" => [
                    "categories" => $categorieManager->findAll()
                ]
            ];
        }

        /**
         * Permet d'ajouter un topic
         */
        public function ajouterTopic(){
            /* On utilise les managers nécessaires */
            $categorieManager = new CategorieManager();
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* Si le formulaire fonctionne */
            if(isset($_POST["submitTopic"])){
                
                /* On filtre les inputs */
                $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $categorie = filter_input(INPUT_POST, "categorie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $contenu = filter_input(INPUT_POST, "contenu", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                /* Si le filtrage fonctionne */
                if($titre && $categorie && $contenu){
                    /* On ajoute un topic et on récupère son id */
                    $id = $topicManager->add([
                        "titre" => $titre,
                        "membre_id" => Session::getUser()->getId(),
                        "categorie_id" => $categorie
                    ]);
                    
                    /* On ajoute le premier post du topic */
                    if($id && $postManager->add([
                        "contenu" => $contenu,
                        "membre_id" => Session::getUser()->getId(),
                        "topic_id" => $id
                    ])){
                        $session->addFlash("success", "Ajout réussi !");
                        return [
                            "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                            "data" => [
                                "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"])
                            ]
                        ];
                    }
                    else{
                        $session->addFlash("error", "Echec de l'ajout !");
                        return [
                            "view" => VIEW_DIR . "forum/Topic/ajouterTopic.php",
                            "data" => [
                                "categories" => $categorieManager->findAll()
                            ]
                        ];
                    }
                }
                else{
                    $session->addFlash("error", "Echec de l'ajout !");
                    return [
                        "view" => VIEW_DIR . "forum/Topic/ajouterTopic.php",
                        "data" => [
                            "categories" => $categorieManager->findAll()
                        ]
                    ];
                }
            }
            else{
                $session->addFlash("error", "Echec de l'ajout !");
                return [
                    "view" => VIEW_DIR . "forum/Topic/ajouterTopic.php",
                    "data" => [
                        "categories" => $categorieManager->findAll()
                    ]
                ];
            }
        }

        /**
         * Permet de supprimer un topic avec ses posts inclus
         */
        public function supprimerTopic(){
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* On filtre l'input */
            $idTopic = filter_input(INPUT_GET, "idTopic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            /* Si le filtrage fonctionne */
            if($idTopic){

                /* Si c'est bien le bon membre qui veut supprimer le topic ou si c'est un admin */
                if($session->getUser()->getId() == $topicManager->idDuMembreDuTopic($idTopic) || $session->isAdmin()){

                    /* On supprime les posts du topic puis on supprime le topic */
                    if($postManager->supprimerPostsDuTopic($idTopic) && $topicManager->delete($idTopic)){
                        $session->addFlash("success", "Suppression réussi !");
                        return [
                            "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                            "data" =>[
                                "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"])
                            ]
                        ];
                    }
                    else{
                        $session->addFlash("error", "Echec de la suppression !");
                        return [
                            "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                            "data" =>[
                                "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"])
                            ]
                        ];
                    }
                }
                else{
                    $session->addFlash("error", "Echec de la suppression !");
                    return [
                        "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                        "data" =>[
                            "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"])
                        ]
                    ];
                }
            }
            else{
                $session->addFlash("error", "Echec de la suppression !!");
                return [
                    "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                    "data" =>[
                        "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"])
                    ]
                ];
            }
        }

        /**
         * Permet de verrouiller un topic
         */
        public function verrouillerTopic(){
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* On filtre l'input */
            $idTopic = filter_input(INPUT_GET, "idTopic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if($idTopic){

                /* Si c'est le bon utilisateur qui veut vérouiller le topic ou si c'est l'admin */
                if($session->getUser()->getId() == $topicManager->idDuMembreDuTopic($idTopic) || $session->isAdmin()){

                    /* On vérrouille le topic */
                    if($topicManager->verrouillerTopic($idTopic)){
                        $session->addFlash("success", "Verrouillage réussi !");
                        return [
                            "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                            "data" => [
                                "posts" => $postManager->trouverPostsDansTopic($idTopic),
                                "ancien" => $postManager->trouverPlusAncienPost($idTopic),
                                "topic" => $topicManager->findOneById($idTopic)
                            ]
                        ];
                    }
                    else{
                        $session->addFlash("error", "Echec du verrouillage !");
                        return [
                            "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                            "data" => [
                                "posts" => $postManager->trouverPostsDansTopic($idTopic),
                                "ancien" => $postManager->trouverPlusAncienPost($idTopic),
                                "topic" => $topicManager->findOneById($idTopic)
                            ]
                        ];
                    }
                }
                else{
                    $session->addFlash("error", "Echec du verrouillage !");
                    return [
                        "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                        "data" => [
                            "posts" => $postManager->trouverPostsDansTopic($idTopic),
                            "ancien" => $postManager->trouverPlusAncienPost($idTopic),
                            "topic" => $topicManager->findOneById($idTopic)
                        ]
                    ];
                }
            }
            else{
                $session->addFlash("error", "Echec du verrouillage !");
                return [
                    "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                    "data" => [
                        "posts" => $postManager->trouverPostsDansTopic($idTopic),
                        "ancien" => $postManager->trouverPlusAncienPost($idTopic),
                        "topic" => $topicManager->findOneById($idTopic)
                    ]
                ];
            }
        }

        /**
         * Permet de verrouiller un topic
         */
        public function deverrouillerTopic(){
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* On filtre l'input */
            $idTopic = filter_input(INPUT_GET, "idTopic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /* Si le filtrage fonctionne */
            if($idTopic){

                /* Si c'est bien le bon utilisateur qui veut dévérouiller le topic ou si c'est l'admin */
                if($session->getUser()->getId() == $topicManager->idDuMembreDuTopic($idTopic) || $session->isAdmin()){

                    /* On dévérrouille le topic */
                    if($topicManager->deverrouillerTopic($idTopic)){
                        $session->addFlash("success", "Déverrouillage réussi !");
                        return [
                            "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                            "data" => [
                                "posts" => $postManager->trouverPostsDansTopic($idTopic),
                                "ancien" => $postManager->trouverPlusAncienPost($idTopic),
                                "topic" => $topicManager->findOneById($idTopic)
                            ]
                        ];
                    }
                    else{
                        $session->addFlash("error", "Echec du déverrouillage !");
                        return [
                            "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                            "data" => [
                                "posts" => $postManager->trouverPostsDansTopic($idTopic),
                                "ancien" => $postManager->trouverPlusAncienPost($idTopic),
                                "topic" => $topicManager->findOneById($idTopic)
                            ]
                        ];
                    }
                }
                else{
                    $session->addFlash("error", "Echec du déverrouillage !");
                    return [
                        "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                        "data" => [
                            "posts" => $postManager->trouverPostsDansTopic($idTopic),
                            "ancien" => $postManager->trouverPlusAncienPost($idTopic),
                            "topic" => $topicManager->findOneById($idTopic)
                        ]
                    ];
                }
            }
            else{
                $session->addFlash("error", "Echec du déverrouillage !");
                return [
                    "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                    "data" => [
                        "posts" => $postManager->trouverPostsDansTopic($idTopic),
                        "ancien" => $postManager->trouverPlusAncienPost($idTopic),
                        "topic" => $topicManager->findOneById($idTopic)
                    ]
                ];
            }
        }

        /**
         * Permet d'aller à la page de modification du titre du topic
         */
        public function allerPageModificationTitreTopic(){
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();

            /* On filtre l'input */
            $idTopic = filter_input(INPUT_GET, "idTopic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /* Si le filtrage fonctionne */
            if($idTopic){
                return [
                    "view" => VIEW_DIR . "forum/Topic/modifierTitreTopic.php",
                    "data" => [
                        "topic" => $topicManager->findOneById($idTopic)
                    ]
                ];
            }
            else{
                return [
                    "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                    "data" => [
                        "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"]) // On cherche tout les topic trier du plus récent au plus ancien
                    ]
                ];
            }
        }

        /**
         * Permet de modifier le titre d'un topic
         */
        public function modificationTitreTopic(){
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* On filtre l'input */
            $idTopic = filter_input(INPUT_GET, "idTopic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if(isset($_POST["submitModificationTitreTopic"])){

                /* On filtre les inputs */
                $titreActuel = filter_input(INPUT_POST, "titreActuel", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $nouveauTitre = filter_input(INPUT_POST, "nouveauTitre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                /* Si le filtrage fonctionne */
                if($titreActuel && $nouveauTitre){

                    /* Si le nouveau titre est différent du titre actuel */
                    if($titreActuel != $nouveauTitre){

                        /* Si c'est le bon membre qui veut modifier le titre */
                        if(Session::getUser()->getId() == $topicManager->findOneById($idTopic)->getMembre()->getId()){

                            /* Si le topic n'est pas vérrouiller */
                            if(!$topicManager->findOneById($idTopic)->getVerrouiller()){
                                
                                /* On modifie le titre du topic */
                                if($topicManager->modifierTitreTopic($idTopic, $nouveauTitre)){
                                    $session->addFlash("success", "Modification réussi !");
                                    return [
                                        "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                                        "data" => [
                                            "posts" => $postManager->trouverPostsDansTopic($idTopic),
                                            "ancien" => $postManager->trouverPlusAncienPost($idTopic),
                                            "topic" => $topicManager->findOneById($idTopic)
                                        ]
                                    ];
                                }
                                else{
                                    $session->addFlash("error", "Échec de la modification ! Le nouveau titre doit être différent !");
                                    return [
                                        "view" => VIEW_DIR . "forum/Topic/modifierTitreTopic.php",
                                        "data" => [
                                            "topic" => $topicManager->findOneById($idTopic)
                                        ]
                                    ];
                                }
                            }
                            else{
                                $session->addFlash("error", "Échec de la modification ! Le nouveau titre doit être différent !");
                                return [
                                    "view" => VIEW_DIR . "forum/Topic/modifierTitreTopic.php",
                                    "data" => [
                                        "topic" => $topicManager->findOneById($idTopic)
                                    ]
                                ];
                            }
                        }
                        else{
                            $session->addFlash("error", "Échec de la modification ! Le nouveau titre doit être différent !");
                            return [
                                "view" => VIEW_DIR . "forum/Topic/modifierTitreTopic.php",
                                "data" => [
                                    "topic" => $topicManager->findOneById($idTopic)
                                ]
                            ];
                        }
                    }
                    else{
                        $session->addFlash("error", "Échec de la modification ! Le nouveau titre doit être différent !");
                        return [
                            "view" => VIEW_DIR . "forum/Topic/modifierTitreTopic.php",
                            "data" => [
                                "topic" => $topicManager->findOneById($idTopic)
                            ]
                        ];
                    }
                }
                else{
                    $session->addFlash("error", "Échec de la modification !");
                    return [
                        "view" => VIEW_DIR . "forum/Topic/modifierTitreTopic.php",
                        "data" => [
                            "topic" => $topicManager->findOneById($idTopic)
                        ]
                    ];
                }
            }
            else{
                $session->addFlash("error", "Échec de la modification !");
                return [
                    "view" => VIEW_DIR . "forum/Topic/modifierTitreTopic.php",
                    "data" => [
                        "topic" => $topicManager->findOneById($idTopic)
                    ]
                ];
            }
        }

        public function allerPageAjoutTopicDansCategorie(){
            /* On utilise les managers nécessaires */
            $categorieManager = new CategorieManager();
            $session = new Session();

            /* On filtre l'input */
            $idCategorie = filter_input(INPUT_GET, "idCategorie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /* Si le filtrage fonctionne */
            if($idCategorie){
                return [
                    "view" => VIEW_DIR . "forum/Topic/ajouterTopicDansCategorie.php",
                    "data" => [
                        "categorie" => $categorieManager->findOneById($idCategorie)
                    ]
                ];
            }
            else{
                $session->addFlash("error", "Erreur d'accès à la page d'ajout d'un topic dans une catégorie !");
                return [
                    "view" => VIEW_DIR . "home.php"
                ];
            }
        }

        public function ajouterTopicDansCategorie(){
            /* On utilise les managers nécessaires */
            $categorieManager = new CategorieManager();
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* Si le formulaire fonctionne */
            if(isset($_POST["submitTopicDansCategorie"])){

                /* On filtre les inputs */
                $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $contenu = filter_input(INPUT_POST, "contenu", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $idCategorie = filter_input(INPUT_GET, "idCategorie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                /* Si le filtrage fonctionne */
                if($titre && $contenu && $idCategorie){
                    /* On ajoute un topic et on récupère son id */
                    $id = $topicManager->add([
                        "titre" => $titre,
                        "membre_id" => Session::getUser()->getId(),
                        "categorie_id" => $idCategorie
                    ]);
                    
                    /* On ajoute le premier post du topic */
                    if($id && $postManager->add([
                        "contenu" => $contenu,
                        "membre_id" => Session::getUser()->getId(),
                        "topic_id" => $id
                    ])){
                        $session->addFlash("success", "Ajout réussi !");
                        return [
                            "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                            "data" => [
                                "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"])
                            ]
                        ];
                    }
                    else{
                        $session->addFlash("error", "Echec de l'ajout !");
                        return [
                            "view" => VIEW_DIR . "forum/Topic/ajouterTopic.php",
                            "data" => [
                                "categories" => $categorieManager->findAll()
                            ]
                        ];
                    }
                }
                else{
                    $session->addFlash("error", "Echec de l'ajout !");
                    return [
                        "view" => VIEW_DIR . "forum/Topic/ajouterTopic.php",
                        "data" => [
                            "categories" => $categorieManager->findAll()
                        ]
                    ];
                }
            }
            else{
                $session->addFlash("error", "Echec de l'ajout du topic dans la catégorie !");
                return [
                    "view" => VIEW_DIR . "home.php",
                ];
            }
        }
    }