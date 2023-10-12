<?php
include './class/Utilisateur.class.php';

    class Message {
        public $from;
        public $to;
        public $message;

        public function __construct(User $from, User $to, $message){
            $this->from = $from;
            $this->to = $to;
            $this->message = $message;
        }

        public function getFrom(){
            return $this->from;
        }
        public function setFrom($from){
            $this->from = $from;
        }

        public function getTo(){
            return $this->to;
        }
        public function setTo($to){
            $this->to = $to;
        }

        public function getmessage(){
            return $this->message;
        }
        public function setmessage($message){
            $this->message = $message;
        }
    }
?>