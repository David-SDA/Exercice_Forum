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
            return DAO::delete($sql, ["id" => $id]);
        }

        /**
         * Permet de récupérer le post le plus ancien
         */
        public function trouverPlusAncienPost($id){
            $sql = "SELECT *
                    FROM " . $this->className . "
                    WHERE " . $this->className . ".topic_id = :id
                    AND " . $this->className . ".dateCreation = (
                        SELECT MIN(" . $this->className . ".dateCreation)
                        FROM " . $this->className . "
                        WHERE " . $this->className . ".topic_id = :id
                    )";
            return $this->getOneOrNullResult(
                DAO::select($sql, ["id" => $id], false),
                $this->className
            );
        }
    }