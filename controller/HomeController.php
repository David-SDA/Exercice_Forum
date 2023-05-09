<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\MembreManager;
    use Model\Managers\UserManager;
    use Model\Managers\TopicManager;
    use Model\Managers\PostManager;
    
    class HomeController extends AbstractController implements ControllerInterface{

        /**
         * Permet d'aller à la page home
         */
        public function index(){
            return [
                "view" => VIEW_DIR . "home.php"
            ];
        }

        /**
         * Permet de lister les membres
         */
        public function listerMembres(){
            $this->restrictTo("ROLE_ADMIN");

            /* On utilise les managers nécessaires */
            $membreManager = new MembreManager();
            return [
                "view" => VIEW_DIR . "security/listeMembres.php",
                "data" => [
                    "membres" => $membreManager->findAll(['dateInscription', 'DESC'])
                ]
            ];
        }

        /**
         * Permet d'aller à la page des règles du forum
         */
        public function forumRegles(){
            return [
                "view" => VIEW_DIR . "regles.php"
            ];
        }

        /**
         * Permet d'aller à la page des mentions légales du forum
         */
        public function mentionsLegales(){
            return [
                "view" => VIEW_DIR . "mentionsLegales.php"
            ];
        }

        /*public function ajax(){
            $nb = $_GET['nb'];
            $nb++;
            include(VIEW_DIR."ajax.php");
        }*/
    }
