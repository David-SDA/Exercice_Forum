<?php
    namespace Model\Managers;
    
    use App\Manager;
    use App\DAO;

    class MembreManager extends Manager{

        protected $className = "Model\Entities\Membre";
        protected $tableName = "membre";


        public function __construct(){
            parent::connect();
        }

        public function trouverEmail($email){
            $requete = "SELECT *
                        FROM " . $this->tableName . "
                        WHERE email = :email";
            return $this->getSingleScalarResult(DAO::select($requete, ["email" => $email]));
        }

        public function trouverPseudo($pseudo){
            $requete = "SELECT *
                        FROM " . $this->tableName . "
                        WHERE pseudo = :pseudo";
            return $this->getSingleScalarResult(DAO::select($requete, ["pseudo" => $pseudo]));
        }

    }