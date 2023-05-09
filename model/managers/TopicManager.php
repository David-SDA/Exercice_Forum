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
         * Permet de trouver les topics d'une catégorie
         */
        public function trouverTopicsParCategorie($id, $order){
            $orderQuery = ($order) ? "ORDER BY " . $order[0] . " " . $order[1] : "";
            
            $requete = "SELECT *, (SELECT COUNT(post.id_post)
                                FROM post
                                WHERE post." . $this->tableName . "_id = id_" . $this->tableName . "
                                ) AS nombrePosts
                    FROM " . $this->tableName . " t
                    WHERE t.categorie_id = :id
                    " . $orderQuery;
            return $this->getMultipleResults(
                DAO::select($requete, ["id" => $id]),
                $this->className
            );
        }

        /**
         * Permet de trouver l'id du membre qui a créer un topic
         */
        public function idDuMembreDuTopic($id){
            $requete = "SELECT " . $this->tableName . ".membre_id
                    FROM " . $this->tableName . "
                    WHERE " . $this->tableName . ".id_" . $this->tableName . " = :id";
            return $this->getSingleScalarResult(DAO::select($requete, ["id" => $id], false));
        }

        /**
         * Permet d'obtenir les topics avec le nombre de posts dans chaque topic
         */
        public function trouverTopicAvecNombrePosts($order = null){
            $orderQuery = ($order) ? "ORDER BY " . $order[0] . " " . $order[1] : "";
            
            $requete = "SELECT *, (SELECT COUNT(post.id_post)
                                FROM post
                                WHERE post." . $this->tableName . "_id = id_" . $this->tableName . "
                                ) AS nombrePosts
                    FROM " . $this->tableName . " a
                    ". $orderQuery;
            return $this->getMultipleResults(
                DAO::select($requete),
                $this->className
            );
        }

        /**
         * Permet de verrouiller un topic
         */
        public function verrouillerTopic($id){
            $requete = "UPDATE " . $this->tableName . "
                    SET verrouiller = 1
                    WHERE " . $this->tableName . ".id_" .$this->tableName . " = :id";
            return DAO::update($requete, ["id" => $id]);
        }

        /**
         * Permet de verrouiller un topic
         */
        public function deverrouillerTopic($id){
            $requete = "UPDATE " . $this->tableName . "
                    SET verrouiller = 0
                    WHERE " . $this->tableName . ".id_" .$this->tableName . " = :id";
            return DAO::update($requete, ["id" => $id]);
        }

        /**
         * Permet d'obtenir les topics d'un membre
         */
        public function trouverTopicsMembre($id, $order = null){
            $orderQuery = ($order) ? "ORDER BY " . $order[0] . " " . $order[1] : "";
            
            $requete = "SELECT *, (SELECT COUNT(post.id_post)
                                FROM post
                                WHERE post." . $this->tableName . "_id = id_" . $this->tableName . "
                                ) AS nombrePosts
                    FROM " . $this->tableName . "
                    WHERE " . $this->tableName .".membre_id = :id
                    ". $orderQuery;
            return $this->getMultipleResults(
                DAO::select($requete, ["id" => $id]),
                $this->className
            );
        }

        /**
         * Permet de supprimer les topics d'une catégorie
         */
        public function supprimerTopicsDeCategorie($id){
            $requete = "DELETE FROM " . $this->tableName . "
                        WHERE categorie_id = :id";
            return DAO::delete($requete, ["id" => $id]);
        }
    }