<?php
    namespace Model\Entities;

    use App\Entity;

    final class Categorie extends Entity{

        private $id;
        private $nomCategorie;

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
         * Obtient la valeur du nom de la catégorie
         */ 
        public function getNomCategorie(){
            return $this->nomCategorie;
        }

        /**
         * Définit la valeur du nom de la catégorie
         *
         * @return  self
         */ 
        public function setNomCategorie($nomCategorie){
            $this->nomCategorie = $nomCategorie;
            return $this;
        }
    }