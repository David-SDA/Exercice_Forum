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
                        FROM " . $this->tableName . " m
                        WHERE m.email = :email";
            return $this->getOneOrNullResult(
                DAO::select($requete, ["email" => $email]),
                $this->className
            );
        }

        public function trouverPseudo($pseudo){
            $requete = "SELECT *
                        FROM " . $this->tableName . " m
                        WHERE m.pseudo = :pseudo";
            return $this->getOneOrNullResult(
                DAO::select($requete, ["pseudo" => $pseudo]),
                $this->className
            );
        }

        public function trouverMotDePasse($email){
            $requete = "SELECT *
                        FROM " . $this->tableName . " m
                        WHERE m.email = :email";
            return $this->getSingleScalarResult(
                DAO::select($requete, ["email" => $email])
            );
        }

    }