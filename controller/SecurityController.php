<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\MembreManager;
    use Model\Managers\TopicManager;

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
            $topicManager = new TopicManager(); // En option, le temps de faire le formulaire de connexion

            /* Filtrage des variables post */
            $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $motDePasse = filter_input(INPUT_POST, "motDePasse", FILTER_SANITIZE_SPECIAL_CHARS);
            $motDePasseConfirmation = filter_input(INPUT_POST, "motDePasseConfirmation", FILTER_SANITIZE_SPECIAL_CHARS);
            
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
                "role" => "membre"
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

        /**
         * Permet d'aller à la page de connexion
         */
        public function allerPageConnexion(){
            return ["view" => VIEW_DIR . "security/connexion.php"];
        }
    }