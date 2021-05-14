<?php
    class Person{
        private $pid;
        private $name;
        private $email;

        public function __construct($pid, $name, $email){
            $this->pid = $pid;
            $this->name = $name;
            $this->email = $email;
        }

        public function getPid(){
            return $this->pid;
        }

        public function getName(){
            return $this->name;
        }

        public function getEmail(){
            return $this->email;
        }


        public function setName($name){
            $this->name = $name;
        }

        public function setEmail($email){
            $this->email = $email;
        }
    }

    class User extends Person{
        private $company;

        public function __construct($pid, $name, $email, $company){
            parent::__construct($pid, $name, $email);
            $this->company = $company;
        }

        public function getCompany(){
            return $this->company;
        }

        public function setCompany($company){
            $this->company = $company;
        }
    }

    class Admin extends Person{
        private $admin;

        public function __construct($pid, $name, $email, $admin){
            parent::__construct($pid, $name, $email);
            $this->admin = $admin;
        }

        public function getAdmin(){
            return $this->admin;
        }
    }

?>