<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\MembreManager;
    use Model\Managers\TopicManager;
    use Model\Managers\PostManager;

    class SecurityController extends AbstractController implements ControllerInterface{
        
        public function index(){}

        /**
         * Permet d'aller à la page d'inscription
         */
        public function allerPageInscription(){
            return ["view" => VIEW_DIR . "security/inscription.php"];
        }

        /**
         * Permet de s'incrire
         */
        public function inscription(){
            $sessionManager = new Session(); // Pour pouvoir utiliser le message flash
            $membreManager = new MembreManager(); // Pour la gestion de la base de données membre

            if(isset($_POST["submitInscription"])){

                /* Filtrage des variables post */
                $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
                $motDePasse = filter_input(INPUT_POST, "motDePasse", FILTER_SANITIZE_SPECIAL_CHARS);
                $motDePasseConfirmation = filter_input(INPUT_POST, "motDePasseConfirmation", FILTER_SANITIZE_SPECIAL_CHARS);
                
                /* Si le filtrage est réussi */
                if($pseudo && $email && $motDePasse && $motDePasseConfirmation){

                    /* Si l'email existe, on indique visuellement que celui-ci existe déjà et on le redirige vers le formulaire d'inscription */
                    if($membreManager->trouverEmail($email)){
                        $sessionManager->addFlash("error", "L'email saisit existe déjà ! Saisissez-en un autre !");
                        return [
                            "view" => VIEW_DIR."security/inscription.php",
                        ];
                    }

                    /* Si le pseudo existe, on indique visuellement que celui-ci existe déjà et on le redirige vers le formulaire d'inscription  */
                    if($membreManager->trouverPseudo($pseudo)){
                        $sessionManager->addFlash("error", "Le pseudo saisit existe déjà ! Saisissez-en un autre !");
                        return [
                            "view" => VIEW_DIR."security/inscription.php",
                        ];
                    }

                    /* Lorsque les deux mots de passe ne sont pas identiques, on l'indique visuellement et on le redirige vers le formulaire d'inscription */
                    if($motDePasse != $motDePasseConfirmation){
                        $sessionManager->addFlash("error", "Les mots de passe ne sont pas identiques ! Veuillez les saisirs à nouveau !");
                        return [
                            "view" => VIEW_DIR."security/inscription.php",
                        ];
                    }

                    /* On hash le mot de passe */
                    $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

                    /* On ajoute le membre :
                    si cela fonctionne, on redirige l'utilisateur vers le formulaire de connexion
                    sinon on le redirige vers le formulaire d'inscription */
                    if($membreManager->add([
                        "pseudo" => $pseudo,
                        "email" => $email,
                        "motDePasse" => $motDePasseHash,
                        "role" => "ROLE_MEMBER"
                    ])){
                        $sessionManager->addFlash("success", "Inscription réussi ! Connectez-vous !");
                        return ["view" => VIEW_DIR."security/connexion.php"];
                    }
                    else{
                        $sessionManager->addFlash("error", "Échec de l'inscription ! ");
                        return [
                            "view" => VIEW_DIR."security/inscription.php",
                        ];
                    }
                }
            }
            $sessionManager->addFlash("error", "Échec de l'inscription !");
            return [
                "view" => VIEW_DIR."security/inscription.php",
            ];
        }

        /**
         * Permet d'aller à la page de connexion
         */
        public function allerPageConnexion(){
            return ["view" => VIEW_DIR . "security/connexion.php"];
        }

        /**
         * Permet de se connecter
         */
        public function connexion(){
            $sessionManager = new Session(); // Pour pouvoir utiliser le message flash
            $membreManager = new MembreManager(); // Pour la gestion de la base de données membre

            if(isset($_POST["submitConnexion"])){
                /* Filtrage des variables post */
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
                $motDePasse = filter_input(INPUT_POST, "motDePasse", FILTER_SANITIZE_SPECIAL_CHARS);

                /* Si le filtrage est réussi */
                if($email && $motDePasse){
                    $motDePasseBdd = $membreManager->trouverMotDePasse($email); // On cherche le mot de passe associé à l'adresse mail
                    /* Si on trouve bien le mot de passe */
                    if($motDePasseBdd){
                        $hash = $motDePasseBdd->getMotDePasse(); // On récupère le hash
                        $membre = $membreManager->trouverEmail($email); // On récupère l'utilisateur
                        /* Si le mot de passe correspond au hachage */
                        if(password_verify($motDePasse, $hash)){
                            $sessionManager->addFlash("success", "Connexion réussi !"); // On l'indique visuellement
                            Session::setUser($membre); // On stocke le membre en session
                            return [
                                "view" => VIEW_DIR . "home.php"
                            ];
                        }
                        else{
                            $sessionManager->addFlash("error", "L'email ou le mot de passe n'est pas bon ! Réessayez"); // On indique que la connexion a échoué
                            return [
                                "view" => VIEW_DIR . "security/connexion.php" // On retourne alors au formulaire d'inscription
                            ];
                        }
                    }
                    else{
                        $sessionManager->addFlash("error", "Échec de la connexion ! Réessayez"); // On indique que la connexion a échoué
                        return [
                            "view" => VIEW_DIR . "security/connexion.php" // On retourne alors au formulaire d'inscription
                        ];
                    }
                }
            }
            else{
                $sessionManager->addFlash("error", "L'email ou le mot de passe n'est pas bon ! Réessayez"); // On indique que la connexion a échoué
                return [
                    "view" => VIEW_DIR . "security/connexion.php" // On retourne alors au formulaire d'inscription
                ];
            }
        }

        /**
         * Permet d'aller à la page du profil d'un membre
         */
        public function allerPageProfil(){
            $membreManager = new MembreManager();
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            return [
                "view" => VIEW_DIR . "security/profil.php",
                "data" => [
                    "nombreTopics" => $membreManager->nombreTopicsDeMembre(Session::getUser()->getId()),
                    "nombrePosts" => $membreManager->nombrePostsDeMembre(Session::getUser()->getId()),
                    "topics" => $topicManager->trouverTopicsMembre(Session::getUser()->getId(), ["dateCreation", "DESC"]),
                    "derniersPosts" => $postManager->trouverCinqDernierPost(Session::getUser()->getId())
                ]
            ];
        }

        /**
         * Permet de se déconnecter
         */
        public function deconnexion(){
            $session = new Session();
            if(session_unset() && session_destroy()){
                $session->addFlash("success", "Déconnexion réussi !");
            }
            else{
                $session->addFlash("error", "Echec de la déconnexion !");
            }
            return [
                "view" => VIEW_DIR . "home.php"
            ];
        }

        /**
         * Permet d'aller à la page de modification du mot de passe
         */
        public function allerPageModificationMotDePasse(){
            return[
                "view" => VIEW_DIR . "security/modificationMotDePasse.php"
            ];
        }

        /**
         * Permet de changer le mot de passe
         */
        public function modificationMotDePasse(){
            $sessionManager = new Session(); // Pour pouvoir utiliser le message flash
            $membreManager = new MembreManager(); // Pour la gestion de la base de données membre
            $topicManager = new TopicManager();
            $postManager = new PostManager();

            if(isset($_POST["submitModificationMotDePasse"])){
                /* Filtrage des input */
                $ancienMotDePasse = filter_input(INPUT_POST, "ancienMotDePasse", FILTER_SANITIZE_EMAIL);
                $nouveauMotDePasse = filter_input(INPUT_POST, "nouveauMotDePasse", FILTER_SANITIZE_SPECIAL_CHARS);
                $motDePasseConfirmation = filter_input(INPUT_POST, "motDePasseConfirmation", FILTER_SANITIZE_SPECIAL_CHARS);

                /* Si le filtrage est réussi */
                if($ancienMotDePasse && $nouveauMotDePasse && $motDePasseConfirmation){
                    $motDePasseBdd = $membreManager->trouverMotDePasse(Session::getUser()->getEmail()); // On cherche le mot de passe associé à l'adresse mail de l'utilisateur
                    
                    /* Si on trouve bien le mot de passe */
                    if($motDePasseBdd){
                        $hash = $motDePasseBdd->getMotDePasse(); // On récupère le hash
                        
                        /* Si le mot de passe correspond au hachage */
                        if(password_verify($ancienMotDePasse, $hash)){
                            
                            /* Lorsque les deux mots de passe ne sont pas identiques, on l'indique visuellement et on le redirige vers le formulaire*/
                            if($nouveauMotDePasse != $motDePasseConfirmation){
                                $sessionManager->addFlash("error", "Les mots de passe ne sont pas identiques ! Veuillez les saisir à nouveau !");
                                return [
                                    "view" => VIEW_DIR."security/modificationMotDePasse.php",
                                ];
                            }
                            
                            if($ancienMotDePasse != $nouveauMotDePasse){
                                /* On hash le mot de passe */
                                $motDePasseHash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);

                                if($membreManager->modificationMotDePasse(Session::getUser()->getId(), $motDePasseHash)){
                                    $sessionManager->addFlash("success", "Changement de mot de passe réussi !");
                                    return [
                                        "view" => VIEW_DIR . "security/profil.php",
                                        "data" => [
                                            "nombreTopics" => $membreManager->nombreTopicsDeMembre(Session::getUser()->getId()),
                                            "nombrePosts" => $membreManager->nombrePostsDeMembre(Session::getUser()->getId()),
                                            "topics" => $topicManager->trouverTopicsMembre(Session::getUser()->getId(), ["dateCreation", "DESC"]),
                                            "derniersPosts" => $postManager->trouverCinqDernierPost(Session::getUser()->getId())
                                        ]
                                    ];
                                }
                                else{
                                    $sessionManager->addFlash("error", "Échec du changement de mot de passe !");
                                    return [
                                        "view" => VIEW_DIR."security/modificationMotDePasse.php",
                                    ];
                                }
                            }
                            else{
                                $sessionManager->addFlash("error", "Échec du changement de mot de passe ! Le nouveau mot de passe doit être différent de l'ancien !");
                                return [
                                    "view" => VIEW_DIR."security/modificationMotDePasse.php",
                                ];
                            }
                        }
                        else{
                            $sessionManager->addFlash("error", "L'ancien mot de passe n'est pas bon");
                            return [
                                "view" => VIEW_DIR."security/modificationMotDePasse.php",
                            ];
                        }
                    }
                    else{
                        $sessionManager->addFlash("error", "Échec du changement de mot de passe !");
                        return [
                            "view" => VIEW_DIR."security/modificationMotDePasse.php",
                        ];
                    }
                }
                else{
                    $sessionManager->addFlash("error", "Échec du changement de mot de passe !");
                    return [
                        "view" => VIEW_DIR."security/modificationMotDePasse.php",
                    ];
                }
            }
            else{
                $sessionManager->addFlash("error", "Échec du changement de mot de passe !");
                return [
                    "view" => VIEW_DIR."security/modificationMotDePasse.php",
                ];
            }
        }
    }