<?php
    namespace Model\Managers;
    
    use App\Manager;
    use App\DAO;

    class MembreManager extends Manager{

        protected $className = "Model\Entities\Membre";
        protected $tableName = "membre";


        public function __construct(){
            parent::connect();
        }


    }