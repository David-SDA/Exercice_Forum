<?php
    namespace Model\Managers;
    
    use App\Manager;
    use App\DAO;

    class TopicManager extends Manager{

        protected $className = "Model\Entities\Topic";
        protected $tableName = "topic";


        public function __construct(){
            parent::connect();
        }

        /**
         * Permet de trouver les topics d'un catégorie
         */
        public function trouverTopicsParCategorie($id){
            $sql = "SELECT *
                    FROM " . $this->tableName . " t
                    WHERE t.categorie_id = :id
                    ORDER BY t.dateCreation DESC";
            return $this->getMultipleResults(
                DAO::select($sql, ["id" => $id]),
                $this->className
            );
        }

    }