<?php
class User {
    public $id;
    public $gender;
    public $firstname;
    public $lastname;
    public $adress;
    public $postcode;
    public $city;
    public $email;
    public $password;
    public $payment_info;
    public $is_admin;



    function __construct(int $id, int $gender, string $fn, string $ln, string $adress, int $postcode, string $city,
     string $email, string $password, string $payment_info, int $is_admin) {
        $this->id = $id;
        $this->gender = $gender;
        $this->firstname = $fn;
        $this->lastname=$ln;
        $this->adress = $adress;
        $this->postcode = $postcode;
        $this->city = $city;
        $this->mail = $email;
        $this->password = $password;
        $this->payment_info = $payment_info;
        $this->is_admin = $is_admin;
      }
}
