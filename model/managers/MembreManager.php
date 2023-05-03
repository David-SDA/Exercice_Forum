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

        /**
         * Permet de trouver un membre par l'email
         */
        public function trouverEmail($email){
            $requete = "SELECT *
                        FROM " . $this->tableName . " m
                        WHERE m.email = :email";
            return $this->getOneOrNullResult(
                DAO::select($requete, ["email" => $email], false),
                $this->className
            );
        }

        /**
         * Permet de trouver un membre par l'email
         */
        public function trouverPseudo($pseudo){
            $requete = "SELECT *
                        FROM " . $this->tableName . " m
                        WHERE m.pseudo = :pseudo";
            return $this->getOneOrNullResult(
                DAO::select($requete, ["pseudo" => $pseudo], false),
                $this->className
            );
        }

        /**
         * Permet de trouver le mot de passe associé à l'email
         */
        public function trouverMotDePasse($email){
            $requete = "SELECT motDePasse
                        FROM " . $this->tableName . " m
                        WHERE m.email = :email";
            return $this->getOneOrNullResult(
                DAO::select($requete, ["email" => $email], false),
                $this->className
            );
        }

    }