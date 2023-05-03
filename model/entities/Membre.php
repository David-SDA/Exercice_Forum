<?php
    namespace Model\Entities;

    use App\Entity;

    final class Membre extends Entity{

        private $id;
        private $pseudo;
        private $email;
        private $motDePasse;
        private $dateInscription;
        private $role;

        public function __construct($data){         
            $this->hydrate($data);        
        }

        /**
         * Obtient la valeur de l'id
         */ 
        public function getId(){
            return $this->id;
        }

        /**
         * Définit la valeur de l'id
         *
         * @return  self
         */ 
        public function setId($id){
            $this->id = $id;
            return $this;
        }

        /**
         * Obtient la valeur du pseudo
         */ 
        public function getPseudo(){
            return $this->pseudo;
        }

        /**
         * Définit la valeur du pseudo
         *
         * @return  self
         */ 
        public function setPseudo($pseudo){
            $this->pseudo = $pseudo;
            return $this;
        }

        /**
         * Obtient la valeur de l'email
         */ 
        public function getEmail(){
            return $this->email;
        }

        /**
         * Définit la valeur de l'email
         *
         * @return  self
         */ 
        public function setEmail($email){
            $this->email = $email;
            return $this;
        }

        /**
         * Obtient la valeur du mot de passe
         */ 
        public function getMotDePasse(){
            return $this->motDePasse;
        }

        /**
         * Définit la valeur du mot de passe
         *
         * @return  self
         */ 
        public function setMotDePasse($motDePasse){
            $this->motDePasse = $motDePasse;
            return $this;
        }

        /**
         * Obtient la valeur de la date d'inscription
         */
        public function getDateInscription(){
            $formattedDate = $this->dateInscription->format("d/m/Y, H:i:s");
            return $formattedDate;
        }

        /**
         * Définit la valeur de la date d'inscription
         * 
         * @return self
         */
        public function setDateInscription($date){
            $this->dateInscription = new \DateTime($date);
            return $this;
        }

        /**
         * Obtient la valeur du rôle
         */ 
        public function getRole(){
            return $this->role;
        }

        /**
         * Définit la valeur du rôle
         *
         * @return  self
         */ 
        public function setRole($role){
            $this->role = $role;
            return $this;
        }

        /**
         * Permet de vérifier le rôle d'un membre
         */
        public function hasRole($role){
            return $this->role == $role;
        }

        /**
         * Méthode toString de la classe
         */
        public function __toString(){
            return $this->pseudo . "";
        }
    }
?>