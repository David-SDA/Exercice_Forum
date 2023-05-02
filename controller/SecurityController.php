<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;

    class SecurityController extends AbstractController implements ControllerInterface{
        
        public function index(){}

        public function allerPageInscription(){
            return ["view" => VIEW_DIR . "security/inscription.php"];
        }
    }