<?php
    namespace Model\Managers;
    
    use App\Manager;
    use App\DAO;

    class PostManager extends Manager{

        protected $className = "Model\Entities\Post";
        protected $tableName = "post";


        public function __construct(){
            parent::connect();
        }

        /**
         * Permet de trouver les posts d'un topic
         */
        public function trouverPostsDansTopic($id){
            $sql = "SELECT *
                    FROM " . $this->tableName . " p
                    WHERE p.topic_id = :id
                    ORDER BY p.dateCreation ASC";
            return $this->getMultipleResults(
                DAO::select($sql, ["id" => $id]),
                $this->className
            );
        }

        /**
         * Permet de supprimer les posts du topic
         */
        public function supprimerPostsDuTopic($id){
            $sql = "DELETE FROM " . $this->tableName . "
                    WHERE " . $this->tableName .  ".topic_id = :id";
            return DAO::delete($sql, ["id" => $id], false);
        }

        /**
         * Permet de récupérer le post le plus ancien grâce à l'id du topic
         */
        public function trouverPlusAncienPost($id){
            $sql = "SELECT " . $this->tableName . ".id_" . $this->tableName . "
                    FROM " . $this->tableName . "
                    WHERE " . $this->tableName . ".topic_id = :id
                    AND " . $this->tableName . ".dateCreation = (
                        SELECT MIN(" . $this->tableName . ".dateCreation)
                        FROM " . $this->tableName . "
                        WHERE " . $this->tableName . ".topic_id = :id)";
            return $this->getSingleScalarResult(DAO::select($sql, ["id" => $id], false));
        }
    }