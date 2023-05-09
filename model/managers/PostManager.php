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

        /**
         * Permet de trouver l'id du membre qui a écrit un post
         */
        public function trouverIdMembrePost($id){
            $sql = "SELECT " . $this->tableName . ".membre_id
                    FROM " . $this->tableName . "
                    WHERE " . $this->tableName . ".id_" . $this->tableName . " = :id";
            return $this->getSingleScalarResult(DAO::select($sql, ["id" => $id], false));
        }

        /**
         * Permet de trouver les 5 derniers post d'un membre
         */
        public function trouverCinqDernierPost($id){
            $sql = "SELECT *
                    FROM " . $this->tableName . "
                    WHERE membre_id = :id
                    ORDER BY dateCreation DESC
                    LIMIT 5";
            return $this->getMultipleResults(
                DAO::select($sql, ["id" => $id]),
                $this->className
            );
        }

        /**
         * Permet de supprimer les posts de différents topics qui ont une catégorie spécifique
         */
        public function supprimerPostsDeTopicsDansCategorie($id){
            $requete = "DELETE FROM " . $this->tableName . "
                        WHERE topic_id IN
                            (SELECT id_topic
                            FROM topic
                            WHERE categorie_id = :id)";
            return DAO::delete($requete, ["id" => $id]);
        }

        /**
         * Permet de modifier un le contenu d'un post
         */
        public function modifierContenuPost($id, $contenu){
            $requete = "UPDATE " . $this->tableName . "
                        SET contenu = :contenu, dateDerniereModification = NOW()
                        WHERE id_" . $this->tableName . " = :id";
            return DAO::update($requete, ["id" => $id, "contenu" => $contenu]);
        }
    }