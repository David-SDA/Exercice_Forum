<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\TopicManager;

    class TopicController extends AbstractController implements ControllerInterface{
        
        public function index(){
            $topicManager = new TopicManager();

            return [
                "view" => VIEW_DIR."forum/Topic/listerTopics.php",
                "data" => [
                    "topics" => $topicManager->findAll(["dateCreation", "DESC"])
                ]
            ];
        }

        public function listerTopicsDansCategorie(){
            $topicManager = new TopicManager();

            return [
                "view" => VIEW_DIR . "forum/Topic/listerTopicsDansCategorie.php",
                "data" => [
                    "topics" => $topicManager->trouverTopicsParCategorie($_GET["id"])
                ]
            ];
        }
    }