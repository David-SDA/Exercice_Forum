<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\PostManager;
    use Model\Managers\TopicManager;

    class PostController extends AbstractController implements ControllerInterface{
        
        public function index(){}

        /**
         * Permet de lister les posts d'un topic
         */
        public function listerPostsDansTopic(){
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();
            $postManager = new PostManager();

            /* On filtre l'input */
            $idTopic = filter_input(INPUT_GET, "idTopic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            /* Si le filtrage fonctionne */
            if($idTopic){
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
                return [
                    "view" => VIEW_DIR . "forum/Topic/listerTopics.php",
                    "data" => [
                        "topics" => $topicManager->trouverTopicAvecNombrePosts(["dateCreation", "DESC"]) // On cherche tout les topic trier du plus récent au plus ancien
                    ]
                ];
            }
        }

        /**
         * Permet d'aller à la page d'ajout d'un post
         */
        public function allerPageAjoutPost(){
            return [
                "view" => VIEW_DIR . "forum/Post/ajouterPost.php"
            ];
        }

        /**
         * Permet d'ajoute un post
         */
        public function ajouterPost(){
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* Si le formulaire fonctionne */
            if(isset($_POST["ajouterPost"])){
                
                /* On filtre les inputs */
                $contenu = filter_input(INPUT_POST, "contenu", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $idTopic = filter_input(INPUT_GET, "idTopic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                /* Si le filtrage fonctionne */
                if($contenu && $idTopic){
                    
                    /* Si le topic n'est pas vérrouiller */    
                    if(!$topicManager->findOneById($idTopic)->getVerrouiller()){
                        
                        /* On ajoute le post */
                        if($postManager->add([
                            "contenu" => $contenu,
                            "membre_id" => Session::getUser()->getId(),
                            "topic_id" => $idTopic
                        ])){
                            $session->addFlash("success", "Ajout du post dans le topic '" . $topicManager->findOneById($idTopic)->getTitre() . "' réussi !");
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
                            $session->addFlash("error", "Échec de l'ajout du post !");
                            return [
                                "view" => VIEW_DIR . "forum/Post/ajouterPost.php"
                            ];
                        }
                    }
                    else{
                        $session->addFlash("error", "Échec de l'ajout du post !");
                        return [
                            "view" => VIEW_DIR . "forum/Post/ajouterPost.php"
                        ];
                    }
                }
                else{
                    $session->addFlash("error", "Échec de l'ajout du post !");
                    return [
                        "view" => VIEW_DIR . "forum/Post/ajouterPost.php"
                    ];
                }
            }
            else{
                $session->addFlash("error", "Échec de l'ajout du post !");
                return [
                    "view" => VIEW_DIR . "forum/Post/ajouterPost.php"
                ];
            }
        }

        /**
         * Permet de supprimer un post
         */
        public function supprimerPost(){
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            if(!$session->getUser()->hasRole("ROLE_BAN")){
                /* On filtre les inputs */
                $idPost = filter_input(INPUT_GET, "idPost", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $idTopic = filter_input(INPUT_GET, "idTopic", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                /* Si le filtrage fonctionne */
                if($idPost && $idTopic){

                    /* Si le topic n'est pas vérouiller */
                    if(!$topicManager->findOneById($idTopic)->getVerrouiller()){
                        
                        /* Si c'est bien le bon membre qui veut supprimer le post ou si c'est un admin */
                        if($session->getUser()->getId() == $postManager->trouverIdMembrePost($idPost) || $session->isAdmin()){
        
                            /* On supprime le post */
                            if($postManager->delete($idPost)){
                                $session->addFlash("success", "Suppression du post réussi !");
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
                                $session->addFlash("error", "Échec de la suppression du post !");
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
                            $session->addFlash("error", "Échec de la suppression du post !");
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
                        $session->addFlash("error", "Échec de la suppression du post !");
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
                    $session->addFlash("error", "Échec de la suppression du post !");
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
                $session->addFlash("error", "Échec de la suppression du post !");
                return [
                    "view" => VIEW_DIR . "home.php",
                ];
            }
        }

        /**
         * Permet d'aller à la page de modification d'un post
         */
        public function allerPageModificationPost(){
            /* On utiliser les managers nécessaires */
            $postManager = new PostManager();
            
            /* On filtre les inputs */
            $idPost = filter_input(INPUT_GET, "idPost", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /* Si le filtrage fonctionne */
            if($idPost){
                return [
                    "view" => VIEW_DIR . "forum/Post/modifierPost.php",
                    "data" => [
                        "post" => $postManager->findOneById($idPost)
                    ]
                ];
            }
            else{
                return [
                    "view" => VIEW_DIR . "home.php"
                ];
            }
        }

        /**
         * Permet de modifier un post
         */
        public function modificationPost(){
            /* On utilise les managers nécessaires */
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();


            /* Si le formulaire fonctionne */
            if(isset($_POST["submitModificationPost"])){

                /* On filtre les inputs */
                $postActuel = filter_input(INPUT_POST, "postActuel", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $nouveauPost = filter_input(INPUT_POST, "nouveauPost", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $idPost = filter_input(INPUT_GET, "idPost", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                /* Si le filtrage fonctionne */
                if($idPost && $postActuel && $nouveauPost){

                    /* Si le topic n'est pas vérouiller */
                    if(!$postManager->findOneById($idPost)->getTopic()->getVerrouiller()){
                        
                        /* Si le post qu'on veut modifier est bien celui du membre en session */
                        if($session->getUser()->getId() == $postManager->findOneById($idPost)->getMembre()->getId()){
                            
                            /* Si le contenu des deux posts ne sont pas identiques */
                            if($postActuel != $nouveauPost){
                                
                                /* On fait la modification de post avec une update de la date de dernière modification */
                                if($postManager->modifierContenuPost($idPost, $nouveauPost)){
                                    $session->addFlash("success", "Le post a été modifié !");
                                    return [
                                        "view" => VIEW_DIR . "forum/Post/listerPostsDansTopic.php",
                                        "data" => [
                                            "posts" => $postManager->trouverPostsDansTopic($postManager->findOneById($idPost)->getTopic()->getId()),
                                            "ancien" => $postManager->trouverPlusAncienPost($postManager->findOneById($idPost)->getTopic()->getId()),
                                            "topic" => $topicManager->findOneById($postManager->findOneById($idPost)->getTopic()->getId())
                                        ]
                                    ];
                                }
                                else{
                                    $session->addFlash("error", "Erreur de la modification du post !");
                                    return [
                                        "view" => VIEW_DIR . "forum/Post/modifierPost.php",
                                        "data" => [
                                            "post" => $postManager->findOneById($idPost)
                                        ]
                                    ];
                                }
                            }
                            else{
                                $session->addFlash("error", "Erreur de la modification du post !");
                                return [
                                    "view" => VIEW_DIR . "forum/Post/modifierPost.php",
                                    "data" => [
                                        "post" => $postManager->findOneById($idPost)
                                    ]
                                ];
                            }
                        }
                        else{
                            $session->addFlash("error", "Erreur de la modification du post !");
                            return [
                                "view" => VIEW_DIR . "forum/Post/modifierPost.php",
                                "data" => [
                                    "post" => $postManager->findOneById($idPost)
                                ]
                            ];
                        }

                    }
                    else{
                        $session->addFlash("error", "Erreur de la modification du post !");
                        return [
                            "view" => VIEW_DIR . "forum/Post/modifierPost.php",
                            "data" => [
                                "post" => $postManager->findOneById($idPost)
                            ]
                        ];

                    }
                }
                else{
                    $session->addFlash("error", "Erreur de la modification du post !");
                    return [
                        "view" => VIEW_DIR . "forum/Post/modifierPost.php",
                        "data" => [
                            "post" => $postManager->findOneById($idPost)
                        ]
                    ];
                }
            }
            else{
                $session->addFlash("error", "Erreur de la modification du post !");
                return [
                    "view" => VIEW_DIR . "home.php",
                ];
            }
        }
    }