<?php
    namespace Model\Managers;
    
    use App\Manager;
    use App\DAO;

    class CategorieManager extends Manager{

        protected $className = "Model\Entities\Categorie";
        protected $tableName = "categorie";


        public function __construct(){
            parent::connect();
        }

        /**
         * Permet de trouver toute les catégories avec le nombre de topic dans celui-ci
         */
        public function trouverCategorieAvecNombreTopic($order = null){
            $orderQuery = ($order) ? "ORDER BY " . $order[0] . " " . $order[1] : "";
            
            $requete = "SELECT *, (SELECT COUNT(topic.id_topic)
                                FROM topic
                                WHERE topic." . $this->tableName . "_id = id_" . $this->tableName . "
                                ) AS nombreTopic
                    FROM " . $this->tableName . " a
                    ". $orderQuery;
            return $this->getMultipleResults(
                DAO::select($requete),
                $this->className
            );
        }

    }