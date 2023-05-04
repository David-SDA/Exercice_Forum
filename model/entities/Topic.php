<?php
    namespace Model\Entities;

    use App\Entity;

    final class Topic extends Entity{

        private $id;
        private $titre;
        private $dateCreation;
        private $verrouiller;
        private $membre;
        private $categorie;
        private $nombrePosts;

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
         * Obtient la valeur du titre
         */ 
        public function getTitre(){
            return $this->titre;
        }

        /**
         * Définit la valeur du titre
         *
         * @return  self
         */ 
        public function setTitre($titre){
            $this->titre = $titre;
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
         * Obtient la valeur de vérouiller
         */ 
        public function getVerrouiller(){
            return $this->verrouiller;
        }
    
        /**
         * Définit la valeur de vérouiller
         *
         * @return  self
         */ 
        public function setVerrouiller($verrouiller){
            $this->verrouiller = $verrouiller;
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
         * Obtient la valeur de la catégorie
         */
        public function getCategorie(){
            return $this->categorie;
        }

        /**
         * Définit la valeur de la catégorie
         * 
         * @return self
         */
        public function setCategorie($categorie){
            $this->categorie = $categorie;
            return $this;
        }

        /**
         * Obtient la valeur du nombre de posts
         */
        public function getNombrePosts(){
            return $this->nombrePosts;
        }

        /**
         * Définit la valeur du nombre de posts
         * 
         * @return self
         */
        public function setNombrePosts($nombrePosts){
            $this->nombrePosts = $nombrePosts;
            return $this;
        }
    }
