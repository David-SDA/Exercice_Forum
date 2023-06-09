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
            return [
                "view" => VIEW_DIR . "security/inscription.php"
            ];
        }

        /**
         * Permet de s'incrire
         */
        public function inscription(){
            /* On utilise les managers nécessaires */
            $membreManager = new MembreManager();
            $session = new Session();

            /* Si le formulaire fonctionne */
            if(isset($_POST["submitInscription"])){

                /* Filtrage des variables post */
                $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
                $motDePasse = filter_input(INPUT_POST, "motDePasse", FILTER_SANITIZE_SPECIAL_CHARS);
                $motDePasseConfirmation = filter_input(INPUT_POST, "motDePasseConfirmation", FILTER_SANITIZE_SPECIAL_CHARS);
                
                /* Si le filtrage est réussi */
                if($pseudo && $email && $motDePasse && $motDePasseConfirmation){

                    /* Si l'email n'existe pas */
                    if(!$membreManager->trouverEmail($email)){
                        
                        /* Si le pseudo n'existe pas */
                        if(!$membreManager->trouverPseudo($pseudo)){
                            
                            /* Si les mot de passe sont identiques */
                            if($motDePasse == $motDePasseConfirmation){
                                
                                /* On hash le mot de passe */
                                $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

                                /* On ajoute le membre */
                                if($membreManager->add([
                                    "pseudo" => $pseudo,
                                    "email" => $email,
                                    "motDePasse" => $motDePasseHash,
                                    "role" => "ROLE_MEMBER"
                                ])){
                                    $session->addFlash("success", "Inscription réussi ! Connectez-vous !");
                                    $this->redirectTo("security", "allerPageConnexion");
                                }
                                else{
                                    $session->addFlash("error", "Échec de l'inscription ! ");
                                    $this->redirectTo("security", "allerPageInscription");
                                }
                            }
                            else{
                                $session->addFlash("error", "Les mots de passe ne sont pas identiques ! Veuillez les saisirs à nouveau !");
                                $this->redirectTo("security", "allerPageInscription");
                            }
                        }
                        else{
                            $session->addFlash("error", "Le pseudo saisit existe déjà ! Saisissez-en un autre !");
                            $this->redirectTo("security", "allerPageInscription");
                        }
                    }
                    else{
                        $session->addFlash("error", "L'email saisit existe déjà ! Saisissez-en un autre !");
                        $this->redirectTo("security", "allerPageInscription");
                    }
                }
                else{
                    $session->addFlash("error", "Échec de l'inscription !");
                    $this->redirectTo("security", "allerPageInscription");
                }
            }
            else{
                $session->addFlash("error", "Échec de l'inscription !");
                $this->redirectTo("security", "allerPageInscription");
            }
        }

        /**
         * Permet d'aller à la page de connexion
         */
        public function allerPageConnexion(){
            return [
                "view" => VIEW_DIR . "security/connexion.php"
            ];
        }

        /**
         * Permet de se connecter
         */
        public function connexion(){
            /* On utilise les managers nécessaires */
            $membreManager = new MembreManager();
            $session = new Session();

            /* Si le formulaire fonctionne */
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
                            Session::setUser($membre); // On stocke le membre en session
                            $session->addFlash("success", "Connexion réussi ! Bienvenue " . Session::getUser()->getPseudo()); // On l'indique visuellement
                            return [
                                "view" => VIEW_DIR . "home.php"
                            ];
                        }
                        else{
                            $session->addFlash("error", "L'email ou le mot de passe n'est pas bon ! Réessayez");
                            $this->redirectTo("security", "allerPageConnexion");
                        }
                    }
                    else{
                        $session->addFlash("error", "Échec de la connexion ! Réessayez");
                        $this->redirectTo("security", "allerPageConnexion");
                    }
                }
                else{
                    $session->addFlash("error", "Échec de la connexion ! Réessayez");
                    $this->redirectTo("security", "allerPageConnexion");
                }
            }
            else{
                $session->addFlash("error", "L'email ou le mot de passe n'est pas bon ! Réessayez");
                $this->redirectTo("security", "allerPageConnexion");
            }
        }

        /**
         * Permet d'aller à la page du profil d'un membre
         */
        public function allerPageProfil(){
            /* On utilise les managers nécessaires */
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
            /* On utilise les managers nécessaires */
            $session = new Session();

            /* On détruit les variables de sessions et on se déconnecte */
            if(session_unset() && session_destroy()){
                $session->addFlash("success", "Déconnexion réussi !");
                return [
                    "view" => VIEW_DIR . "home.php"
                ];
            }
            else{
                $session->addFlash("error", "Échec de la déconnexion !");
                return [
                    "view" => VIEW_DIR . "home.php"
                ];
            }
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
            /* On utilise les managers nécessaires */
            $membreManager = new MembreManager();
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* Si le formulaire fonctionne */
            if(isset($_POST["submitModificationMotDePasse"])){
                
                /* Filtrage des inputs */
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
                            if($nouveauMotDePasse == $motDePasseConfirmation){
                                
                                /* Si le nouveau de mot de passe est différent de l'ancien */
                                if($ancienMotDePasse != $nouveauMotDePasse){
                                    
                                    /* On hash le mot de passe */
                                    $motDePasseHash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);

                                    /* On change le mot de passe */
                                    if($membreManager->modificationMotDePasse(Session::getUser()->getId(), $motDePasseHash)){
                                        $session->addFlash("success", "Modification du mot de passe réussi !");
                                        Session::getUser()->setMotDePasse($motDePasseHash);
                                        $this->redirectTo("security", "allerPageProfil");
                                    }
                                    else{
                                        $session->addFlash("error", "Échec de la modification du mot de passe !");
                                        $this->redirectTo("security", "allerPageModificationMotDePasse");
                                    }
                                }
                                else{
                                    $session->addFlash("error", "Échec de la modification du mot de passe ! Le nouveau mot de passe doit être différent de l'ancien !");
                                    $this->redirectTo("security", "allerPageModificationMotDePasse");
                                }
                            }
                            else{
                                $session->addFlash("error", "Les mots de passe ne sont pas identiques ! Veuillez les saisir à nouveau !");
                                $this->redirectTo("security", "allerPageModificationMotDePasse");
                            }
                        }
                        else{
                            $session->addFlash("error", "L'ancien mot de passe n'est pas bon");
                            $this->redirectTo("security", "allerPageModificationMotDePasse");
                        }
                    }
                    else{
                        $session->addFlash("error", "Échec de la modification du mot de passe !");
                        $this->redirectTo("security", "allerPageModificationMotDePasse");
                    }
                }
                else{
                    $session->addFlash("error", "Échec de la modification du mot de passe !");
                    $this->redirectTo("security", "allerPageModificationMotDePasse");
                }
            }
            else{
                $session->addFlash("error", "Échec de la modification du mot de passe !");
                $this->redirectTo("security", "allerPageModificationMotDePasse");
            }
        }

        /**
         * Permet d'aller à la page de modification de l'email
         */
        public function allerPageModificationEmail(){
            return [
                "view" => VIEW_DIR . "security/modificationEmail.php",
            ];
        }

        /**
         * Permet de modifier l'email
         */
        public function modificationEmail(){
            /* On utilise les managers nécessaires */
            $membreManager = new MembreManager();
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* Si le formulaire fonctionne */
            if(isset($_POST["submitModificationEmail"])){
                
                /* Filtrage des input */
                $ancienEmail = filter_input(INPUT_POST, "ancienEmail", FILTER_SANITIZE_EMAIL);
                $nouveauEmail = filter_input(INPUT_POST, "nouveauEmail", FILTER_SANITIZE_EMAIL);
                $emailConfirmation = filter_input(INPUT_POST, "emailConfirmation", FILTER_SANITIZE_EMAIL);
                $motDePasse = filter_input(INPUT_POST, "motDePasse", FILTER_SANITIZE_SPECIAL_CHARS);

                /* Si le filtrage fonctionne */
                if($ancienEmail && $nouveauEmail && $emailConfirmation && $motDePasse){

                    /* Si l'email rentré correspond bien à celui du membre actuel */
                    if($ancienEmail == Session::getUser()->getEmail()){

                        /* Si le nouvel email est identique à la confirmation de l'email */
                        if($nouveauEmail == $emailConfirmation){

                            /* Si l'ancien email est différent du nouveau */
                            if($ancienEmail != $nouveauEmail){
                                $motDePasseBdd = $membreManager->trouverMotDePasse(Session::getUser()->getEmail()); // On cherche le mot de passe associé à l'adresse mail
                                
                                /* Si l'email n'existe pas */
                                if(!$membreManager->trouverEmail($nouveauEmail)){
                                    
                                    /* Si on récupère bien le mot de passe */
                                    if($motDePasseBdd){
                                        $hash = $motDePasseBdd->getMotDePasse(); // On récupère le hash

                                        /* Si on a le bon mot de passe */
                                        if(password_verify($motDePasse, $hash)){

                                            /* On modifie l'email */
                                            if($membreManager->modificationEmail(Session::getUser()->getId(), $nouveauEmail)){
                                                $session->addFlash("success", "Modification de l'email réussi !");
                                                Session::getUser()->setEmail($nouveauEmail);
                                                $this->redirectTo("security", "allerPageProfil");
                                            }
                                            else{
                                                $session->addFlash("error", "Échec de la modification de l'email");
                                                $this->redirectTo("security", "allerPageModificationEmail");
                                            }
                                        }
                                        else{
                                            $session->addFlash("error", "Échec de la modification de l'email");
                                            $this->redirectTo("security", "allerPageModificationEmail");
                                        }
                                    }
                                    else{
                                        $session->addFlash("error", "Échec de la modification de l'email");
                                        $this->redirectTo("security", "allerPageModificationEmail");
                                    }
                                }
                                else{
                                    $session->addFlash("error", "Échec de la modification de l'email, l'email existe déjà !");
                                    $this->redirectTo("security", "allerPageModificationEmail");
                                }
                            }
                            else{
                                $session->addFlash("error", "Échec de la modification de l'email, le nouvel email doit être différent de l'actuel !");
                                $this->redirectTo("security", "allerPageModificationEmail");
                            }
                        }
                        else{
                            $session->addFlash("error", "Échec de la modification de l'email, la confimation d'email n'est pas identique au nouveau !");
                            $this->redirectTo("security", "allerPageModificationEmail");
                        }
                    }
                    else{
                        $session->addFlash("error", "Échec de la modification de l'email, l'email actuel n'est pas le bon !");
                        $this->redirectTo("security", "allerPageModificationEmail");
                    }
                }
                else{
                    $session->addFlash("error", "Échec de la modification de l'email");
                    $this->redirectTo("security", "allerPageModificationEmail");
                }
            }
            else{
                $session->addFlash("error", "Échec de la modification de l'email");
                $this->redirectTo("security", "allerPageModificationEmail");
            }
        }

        /**
         * Permet d'aller à la page de modification du pseudo
         */
        public function allerPageModificationPseudo(){
            return [
                "view" => VIEW_DIR . "security/modificationPseudo.php"
            ];
        }

        /**
         * Permer de modifier le pseudo
         */
        public function modificationPseudo(){
            /* On utilise les managers nécessaires */
            $membreManager = new MembreManager();
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            $session = new Session();

            /* Si le formulaire fonctionne */
            if(isset($_POST["submitModificationPseudo"])){
                /* Filtrage des input */
                $ancienPseudo = filter_input(INPUT_POST, "ancienPseudo", FILTER_SANITIZE_SPECIAL_CHARS);
                $nouveauPseudo = filter_input(INPUT_POST, "nouveauPseudo", FILTER_SANITIZE_SPECIAL_CHARS);

                /* Si le filtrage fonctionne */
                if($ancienPseudo && $nouveauPseudo){

                    /* Si le nouveau pseudo est différent de l'ancien */
                    if($ancienPseudo != $nouveauPseudo){

                        /* Si le nouveau pseudo n'existe pas */
                        if(!$membreManager->trouverPseudo($nouveauPseudo)){
                            
                            /* On modifie le pseudo */
                            if($membreManager->modificationPseudo(Session::getUser()->getId(), $nouveauPseudo)){
                                $session->addFlash("success", "Modification de pseudo réussi ! Vous êtes maintenant $nouveauPseudo !");
                                Session::getUser()->setPseudo($nouveauPseudo);
                                $this->redirectTo("security", "allerPageProfil");
                            }
                            else{
                                $session->addFlash("error", "Échec de la modification du pseudo !");
                                $this->redirectTo("security", "allerPageModificationPseudo");
                            }
                        }
                        else{
                            $session->addFlash("error", "Échec de la modification du pseudo ! Le nouveau pseudo est déjà utilisé !");
                            $this->redirectTo("security", "allerPageModificationPseudo");
                        }
                    }
                    else{
                        $session->addFlash("error", "Échec de la modification du pseudo ! Les deux pseudo sont identique !");
                        $this->redirectTo("security", "allerPageModificationPseudo");
                    }
                }
                else{
                    $session->addFlash("error", "Échec de la modification du pseudo !");
                    $this->redirectTo("security", "allerPageModificationPseudo");
                }
            }
            else{
                $session->addFlash("error", "Échec de la modification du pseudo !");
                $this->redirectTo("security", "allerPageModificationPseudo");
            }
        }

        /**
         * Permet de bannir un membre
         */
        public function bannir(){
            /* On utilise les managers nécessaires */
            $membreManager = new MembreManager();
            $session = new Session();

            /* On vérifie que l'utilisateur est bien l'admin */
            if($session->isAdmin()){
                
                /* On filtre l'input */
                $idMembre = filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS);

                /* Si le filtrage fonctionne */
                if($idMembre){

                    /* On bannit l'utilisateur */
                    if($membreManager->modificationRole($idMembre, "ROLE_BAN")){
                        $session->addFlash("success", "Vous avez banni le membre " . $membreManager->findOneById($idMembre)->getPseudo());
                        $this->redirectTo("membre");
                    }
                    else{
                        $session->addFlash("error", "Échec du bannissement");
                        $this->redirectTo("membre");
                    }
                }
                else{
                    $session->addFlash("error", "Échec du bannissement");
                    $this->redirectTo("membre");
                }
            }
            else{
                $session->addFlash("error", "Échec du bannissement");
                $this->redirectTo("membre");
            }
        }

        /**
         * Permet de débannir un membre
         */
        public function debannir(){
            /* On utilise les managers nécessaires */
            $membreManager = new MembreManager();
            $session = new Session();

            /* On vérifie que l'utilisateur est bien l'admin */
            if($session->isAdmin()){
                /* On filtre l'input */
                $idMembre = filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS);

                /* Si le filtrage fonctionne */
                if($idMembre){

                    /* On bannit l'utilisateur */
                    if($membreManager->modificationRole($idMembre, "ROLE_MEMBER")){
                        $session->addFlash("success", "Vous avez débanni le membre " . $membreManager->findOneById($idMembre)->getPseudo());
                        $this->redirectTo("membre");
                    }
                    else{
                        $session->addFlash("error", "Échec du débannisssment !");
                        $this->redirectTo("membre");
                    }
                }
                else{
                    $session->addFlash("error", "Échec du débannisssment !");
                    $this->redirectTo("membre");
                }
            }
            else{
                $session->addFlash("error", "Échec du débannisssment !");
                $this->redirectTo("membre");
            }
        }
    }