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

        if ($id !== null) {
            $this->validateId($id);
            $peopleData = $this->DB->query('SELECT * FROM people WHERE id=?', [$id]);

            if (empty($peopleData)) {
                throw new PeopleException('Entries with such id not available');
            } else {
                $this->id = $id;
                $this->firstName = $peopleData[0]['first_name'];
                $this->lastName = $peopleData[0]['last_name'];
                $this->birthDate = $peopleData[0]['birth_date'];
                $this->gender = $peopleData[0]['gender'];
                $this->birthCity = $peopleData[0]['birth_city'];
            }
        } else {
            $this->validateFirstName($firstName);
            $this->validateLastName($lastName);
            $this->validateBirthDate($birthDate);
            $this->validateGender($gender);
            $this->validateBirthCity($birthCity);

            $this->DB->query(
                'INSERT INTO people(first_name, last_name, birth_date, gender, birth_city) VALUES(?,?,?,?,?)',
                [
                    $firstName,
                    $lastName,
                    $birthDate,
                    $gender,
                    $birthCity
                ]
            );

            $this->id = $this->DB->lastInsertId();
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->birthDate = $birthDate;
            $this->gender = $gender;
            $this->birthCity = $birthCity;
        }

    }

    private function validateId($id)
    {
        $options = array(
            'options' => array(
                'min_range' => 1,
            )
        );

        if (filter_var($id, FILTER_VALIDATE_INT, $options) === FALSE) {
            throw new PeopleException('Invalid ID');
        }
    }

    private function validateFirstName($firstName)
    {
        if (empty($firstName) || !preg_match('/^[a-zа-яё]*$/i', $firstName)) {
            throw new PeopleException('Invalid First Name');
        }
    }

    private function validateLastName($lastName)
    {
        if (empty($lastName) || !preg_match('/^[a-zа-яё]*$/i', $lastName)) {
            throw new PeopleException('Invalid Last Name');
        }
    }

    private function validateBirthDate($birthDate)
    {
        if (empty($birthDate) || !preg_match('/^([0-9]{2})/([0-9]{2})/([0-9]{4})$/', $birthDate)) {
            throw new PeopleException('The date of birth is not a valid date in the format MM/DD/YYYY');
        }
    }

    private function validateGender($gender)
    {
        $options = array(
            'options' => array(
                'min_range' => 0,
                'max_range' => 1
            )
        );

        if (filter_var($gender, FILTER_VALIDATE_INT, $options) === FALSE) {
            throw new PeopleException('Invalid gender');
        }
    }

    private function validateBirthCity($birthCity)
    {
        if (empty($birthCity)) {
            throw new PeopleException('Invalid Birth City');
        }
    }
}

class PeopleException extends \Exception
{
}