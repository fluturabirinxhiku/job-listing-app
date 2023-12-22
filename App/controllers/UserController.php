<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Display login form
     *
     * @return void
     */
    public function login()
    {
        loadView('users/login');
    }

    /**
     * Authenticate a user with email and password
     *
     * @return void
     */
    public function authenticate()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];

        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if (!Validation::string($password, 8, 50)) {
            $errors['password'] = 'Password must be at least 8 characters long';
        }

        if (!empty($errors)) {
            loadView('users/login', ['errors' => $errors]);
            exit;
        }
        $params = ['email' => $email];

        $user = $this->db->query("SELECT * FROM users WHERE email = :email", $params)->fetch();

        if (!$user) {
            $errors['email'] = 'Incorrect credentials';
            loadView('users/login', ['errors' => $errors]);
            exit;
        }

        if (!password_verify($password, $user->password)) {
            $errors['email'] = 'Incorrect credentials';
            loadView('users/login', ['errors' => $errors]);
            exit;
        }

        Session::set('user', ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'city' => $user->city, 'country' => $user->country]);
    }

    /**
     * Display register form
     *
     * @return void
     */
    public function register()
    {
        loadView('users/register');
    }

    /**
     * Store user data in the database
     *
     * @return void
     */
    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];
        $city = $_POST['city'];
        $country = $_POST['country'];

        $errors = [];

        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if (!Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 characters';
        }

        if (!Validation::string($password, 8, 50)) {
            $errors['password'] = 'Password must be at least 8 characters long';
        }

        if (!Validation::match($password, $passwordConfirmation)) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        if (!empty($errors)) {
            loadView('users/register', ['errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'city' => $city,
                    'country' => $country
                ]]);
            exit;
        }

        $params = [
            'email' => $email,
        ];

        $user = $this->db->query("SELECT * FROM users WHERE email = :email", $params)->fetch();

        if ($user) {
            $errors['email'] = 'That email already exists';
            loadView('users/register', [
                'errors' => $errors,
            ]);
            exit;
        }

        $params = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'city' => $city,
            'country' => $country
        ];

        $this->db->query("INSERT INTO users(name, email, password,city,country) VALUES(:name,:email,:password,:city,:country)", $params);

        $userId = $this->db->connection->lastInsertId();
        Session::set('user', ['id' => $userId, 'name' => $name, 'email' => $email, 'city' => $city, 'country' => $country]);
        redirect('/');
    }

    /**
     * Log out user and kill session
     *
     * @return void
     */
    public function logout()
    {
        Session::clearAll();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);

        redirect('/');
    }
}