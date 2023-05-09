<?php
    namespace Model\Entities;

    use App\Entity;

    final class Post extends Entity{

        private $id;
        private $dateCreation;
        private $dateDerniereModification;
        private $contenu;
        private $membre;
        private $topic;

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
         * Obtient la valeur de la date de création
         */
        public function getDateCreation(){
            $formattedDate = $this->dateCreation->format("d/m/Y, H:i:s");
            return $formattedDate;
        }

        /**
         * Définit la valeur de la date de création
         * 
         * @return self
         */
        public function setDateCreation($date){
            $this->dateCreation = new \DateTime($date);
            return $this;
        }

        /**
         * Obtient la valeur de la date de dernière modification
         */
        public function getDateDerniereModification(){
            $formattedDate = $this->dateDerniereModification->format("d/m/Y, H:i:s");
            return $formattedDate;
        }

        /**
         * Définit la valeur de la date de dernière modification
         */
        public function setDateDerniereModification($date){
            $this->dateDerniereModification = new \DateTime($date);
            return $this;
        }

        /**
         * Obtient la valeur du contenu
         */ 
        public function getContenu(){
            return $this->contenu;
        }

        /**
         * Définit la valeur du contenu
         *
         * @return  self
         */ 
        public function setContenu($contenu){
            $this->contenu = $contenu;
            return $this;
        }

        /**
         * Obtient la valeur du membre
         */ 
        public function getMembre(){
            return $this->membre;
        }

        /**
         * Définit la valeur du membre
         *
         * @return  self
         */ 
        public function setMembre($membre){
            $this->membre = $membre;
            return $this;
        }

        /**
         * Obtient la valeur du topic
         */
        public function getTopic(){
            return $this->topic;
        }

        /**
         * Définit la valeur du topic
         * 
         * @return self
         */
        public function setTopic($topic){
            $this->topic = $topic;
            return $this;
        }
    }
