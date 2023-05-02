<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\MembreManager;
    use Model\Managers\TopicManager;

    class SecurityController extends AbstractController implements ControllerInterface{
        
        public function index(){}

        public function allerPageInscription(){
            return ["view" => VIEW_DIR . "security/inscription.php"];
        }

        public function inscription(){
            $sessionManager = new Session();
            $membreManager = new MembreManager();
            $topicManager = new TopicManager();

            $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $motDePasse = filter_input(INPUT_POST, "motDePasse", FILTER_SANITIZE_SPECIAL_CHARS);
            $motDePasseConfirmation = filter_input(INPUT_POST, "motDePasseConfirmation", FILTER_SANITIZE_SPECIAL_CHARS);
            
            if($membreManager->trouverEmail($email)){
                $sessionManager->addFlash("error", "L'email saisit existe déjà ! Saisissez-en un autre !");
                return [
                    "view" => VIEW_DIR."security/inscription.php",
                ];
            }
            if($membreManager->trouverPseudo($pseudo)){
                $sessionManager->addFlash("error", "Le pseudo saisit existe déjà ! Saisissez-en un autre !");
                return [
                    "view" => VIEW_DIR."security/inscription.php",
                ];
            }
            if($motDePasse != $motDePasseConfirmation){
                $sessionManager->addFlash("error", "Les mots de passe ne sont pas identiques ! Veuillez les saisirs à nouveau !");
                return [
                    "view" => VIEW_DIR."security/inscription.php",
                ];
            }

            return [
                "view" => VIEW_DIR."forum/Topic/listerTopics.php", // Il faudra rediriger vers le formulaire de connexion
                "data" => [
                    "topics" => $topicManager->findAll(["dateCreation", "DESC"])
                ]
            ];
        }
    }