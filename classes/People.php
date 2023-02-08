<?php

namespace App;

use Db;
use const DBHost;
use const DBName;
use const DBPassword;
use const DBPort;
use const DBUser;

class People
{
    private $id;
    private $firstName;
    private $lastName;
    private $birthDate;
    private $gender;
    private $birthCity;
    private $DB;

    public function __construct($id = null, $firstName = null, $lastName = null, $birthDate = null, $gender = null, $birthCity = null)
    {
        $this->DB = new Db(DBHost, DBPort, DBName, DBUser, DBPassword);
    }

}

class PeopleException extends \Exception
{
}