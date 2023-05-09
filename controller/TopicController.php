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
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /* Si le filtrage fonctionne */
            if($id){
                return [
                    "view" => VIEW_DIR . "forum/Topic/listerTopicsDansCategorie.php",
                    "data" => [
                        "topics" => $topicManager->trouverTopicsParCategorie($id, ["dateCreation", "DESC"]),
                        "categorie" => $categorieManager->findOneById($id)
                    ]
                ];
            }
            else{
                return [
                    "view" => VIEW_DIR . "forum/Categorie/listerCategories.php",
                    "data" => [
                        "categories" => $categorieManager->findAll(["id_categorie", "ASC"])
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
            $sessionManager = new Session();

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
                        $sessionManager->addFlash("success", "Ajout réussi !");
                        return [
                            "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                            "data" => [
                                "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"])
                            ]
                        ];
                    }
                    else{
                        $sessionManager->addFlash("error", "Echec de l'ajout !");
                        return [
                            "view" => VIEW_DIR . "forum/Topic/ajouterTopic.php",
                            "data" => [
                                "categories" => $categorieManager->findAll()
                            ]
                        ];
                    }
                }
                else{
                    $sessionManager->addFlash("error", "Echec de l'ajout !");
                    return [
                        "view" => VIEW_DIR . "forum/Topic/ajouterTopic.php",
                        "data" => [
                            "categories" => $categorieManager->findAll()
                        ]
                    ];
                }
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
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            /* Si le filtrage fonctionne */
            if($id){

                /* Si c'est bien le bon membre qui veut supprimer le topic ou si c'est un admin */
                if($session->getUser()->getId() == $topicManager->idDuMembreDuTopic($id) || $session->isAdmin()){

                    /* On supprime les posts du topic puis on supprime le topic */
                    if($postManager->supprimerPostsDuTopic($id) && $topicManager->delete($id)){
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
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if($id){

                /* Si c'est le bon utilisateur qui veut vérouiller le topic ou si c'est l'admin */
                if($session->getUser()->getId() == $topicManager->idDuMembreDuTopic($id) || $session->isAdmin()){

                    /* On vérrouille le topic */
                    if($topicManager->verrouillerTopic($id)){
                        $session->addFlash("success", "Verrouillage réussi !");
                        return [
                            "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                            "data" => [
                                "posts" => $postManager->trouverPostsDansTopic($id),
                                "ancien" => $postManager->trouverPlusAncienPost($id),
                                "topic" => $topicManager->findOneById($id)
                            ]
                        ];
                    }
                    else{
                        $session->addFlash("error", "Echec du verrouillage !");
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
                else{
                    $session->addFlash("error", "Echec du verrouillage !");
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
            else{
                $session->addFlash("error", "Echec du verrouillage !");
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

        /**
         * Permet de verrouiller un topic
         */
        public function deverrouillerTopic(){
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* On filtre l'input */
            $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /* Si le filtrage fonctionne */
            if($id){

                /* Si c'est bien le bon utilisateur qui veut dévérouiller le topic ou si c'est l'admin */
                if($session->getUser()->getId() == $topicManager->idDuMembreDuTopic($id) || $session->isAdmin()){

                    /* On dévérrouille le topic */
                    if($topicManager->deverrouillerTopic($id)){
                        $session->addFlash("success", "Déverrouillage réussi !");
                        return [
                            "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                            "data" => [
                                "posts" => $postManager->trouverPostsDansTopic($id),
                                "ancien" => $postManager->trouverPlusAncienPost($id),
                                "topic" => $topicManager->findOneById($id)
                            ]
                        ];
                    }
                    else{
                        $session->addFlash("error", "Echec du déverrouillage !");
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
                else{
                    $session->addFlash("error", "Echec du déverrouillage !");
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
            else{
                $session->addFlash("error", "Echec du déverrouillage !");
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
    }