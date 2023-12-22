<?php

namespace App\Controllers;

use Framework\Middleware\Authorize;
use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

class ListingController
{
    protected $db;
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }
    /**
     * Display all listings
     *
     * @return void
     */
    public function index()
    {
        $listings = $this->db->query("SELECT * FROM listings ORDER BY created_at DESC")->fetchAll();

        loadView("listings/index", ['listings' => $listings]);
    }

    /**
     * Display the create listing form
     *
     * @return void
     */
    public function create()
    {
        loadView("listings/create");
    }

    /**
     *  Display single listing
     *
     * @param array $params
     * @return void
     */
    public function show($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)
            ->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }
        loadView("listings/show", ['listing' => $listing]);
    }

    /**
     * Store listing data in database
     *
     * @return void
     */
    public function store()
    {
        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'country', 'phone', 'email', 'requirements', 'benefits'];
        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));
        $newListingData['user_id'] = Session::get('user')['id'];
        $newListingData = array_map('sanitize', $newListingData);

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'country'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }
        if (!empty($errors)) {
            loadView('listings/create', ['errors' => $errors, 'listings' => $newListingData]);
        } else {
            $fields = [];
            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }

            $fields = implode(', ', $fields);

            $values = [];
            foreach ($newListingData as $field => $value) {
                if ($value === '') {
                    $newListingData[$field] = null;
                }
                $values[] = ':' . $field;
            }

            $values = implode(', ', $values);
            $query = "INSERT INTO listings ({$fields}) values ({$values})";
            $this->db->query($query, $newListingData);

            redirect('/listings');
        }
    }

    /**
     * Delete listing
     *
     * @param array $params
     * @return void
     */
    public function delete($params)
    {
        $id = $params['id'];

        $params = ['id' => $id];
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }
        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized to delete this job listing');
            return redirect('/listings/' . $listing->id);
        }

        $this->db->query("DELETE FROM listings WHERE id = :id", $params);

        Session::setFlashMessage('success_message', 'Listing deleted successfully');

        redirect('/listings');
    }

    /**
     * Display the edit listing form
     *
     * @param array $params
     * @return void
     */
    public function edit($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)
            ->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized to update this job listing');
            return redirect('/listings/' . $listing->id);
        }

        loadView("listings/edit", ['listing' => $listing]);
    }

    /**
     * Update a listing
     *
     * @param array $params
     * @return void
     */
    public function update($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)
            ->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized to update this job listing');
            return redirect('/listings/' . $listing->id);
        }

        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'country', 'phone', 'email', 'requirements', 'benefits'];


        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'country'];

        $errors = [];
        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) || Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            loadView('listing/edit', ['listing' => $listing, 'errors' => $errors]);
            exit;
        } else {
            $updateFields = [];

            foreach (array_keys($updateValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }

            $updateFields = implode(",", $updateFields);

            $updateValues['id'] = $id;
            $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";

            $this->db->query($updateQuery, $updateValues);

            Session::setFlashMessage('success_message', 'Listing updated successfully');

            redirect('/listings/' . $id);
        }
    }
}