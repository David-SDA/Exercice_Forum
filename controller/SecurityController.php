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
            $membreManager = new MembreManager;

            $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $motDePasse = filter_input(INPUT_POST, "moDePasse", FILTER_SANITIZE_SPECIAL_CHARS);
            
            if($membreManager->trouverPseudo($pseudo)){
                $sessionManager->addFlash("success", "Trouvé !");
            }
            else{
                $sessionManager->addFlash("error", "Pas trouvé !");
            }

            $topicManager = new TopicManager();
            return [
                "view" => VIEW_DIR."forum/Topic/listerTopics.php",
                "data" => [
                    "topics" => $topicManager->findAll(["dateCreation", "DESC"])
                ]
            ];
        }
    }