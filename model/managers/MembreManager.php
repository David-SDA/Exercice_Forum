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

        /**
         * Permet de compter le nombre de topics écrit par un membre
         */
        public function nombreTopicsDeMembre($id){
            $requete = "SELECT COUNT(t.id_topic) AS nbTopics
                        FROM " . $this->tableName . " m, topic t
                        WHERE m.id_membre = t.membre_id
                        AND m.id_membre = :id";
            return $this->getSingleScalarResult(
                DAO::select($requete, ["id" => $id], false)
            );
        }

        /**
         * Permet de compter le nombre de posts écrit par un membre
         */
        public function nombrePostsDeMembre($id){
            $requete = "SELECT COUNT(p.id_post) AS nbPosts
                        FROM " . $this->tableName . " m, post p
                        WHERE m.id_membre = p.membre_id
                        AND m.id_membre = :id";
            return $this->getSingleScalarResult(
                DAO::select($requete, ["id" => $id], false)
            );
        }

        /**
         * Permet de modifier le mot de passe d'un membre
         */
        public function modificationMotDePasse($id, $mdp){
            $requete = "UPDATE " . $this->tableName . "
                        SET motDePasse = :mdp
                        WHERE id_" . $this->tableName ." = :id";
            return DAO::update($requete, ["id" => $id, "mdp" => $mdp]);
        }

        /**
         * Permet de modifier l'email d'un membre
         */
        public function modificationEmail($id, $email){
            $requete = "UPDATE " . $this->tableName . "
                        SET email = :email
                        WHERE id_" . $this->tableName ." = :id";
            return DAO::update($requete, ["id" => $id, "email" => $email]);
        }
    }