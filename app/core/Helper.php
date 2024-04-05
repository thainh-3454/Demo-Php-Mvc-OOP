<?php

class Helper
{

    public static function testInput($data)
    {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function getFormValues($fields, $defaultFields = [])
    {
        $values = [];
        foreach ($fields as $field) {
            $defaultValue = isset($defaultFields[$field]) ? $defaultFields[$field] : '';
            $values[$field] = isset($_POST[$field]) ? self::testInput($_POST[$field]) : $defaultValue;
        }
        return $values;
    }

    public static function validateFields($fields, &$errors)
    {
        foreach ($fields as $field => $rules) {
            if (!isset($_POST[$field])) {
                continue;
            }

            // Required field validation
            if ($rules['required'] && empty($_POST[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }

            // Minimum length validation
            if (isset($_POST[$field]) && isset($rules['min_length']) && strlen($_POST[$field]) < $rules['min_length']) {
                $errors[$field] = ucfirst($field) . ' must be at least ' . $rules['min_length'] . ' characters';
            }

            // Maximum length validation
            if (isset($_POST[$field]) && isset($rules['max_length']) && strlen($_POST[$field]) > $rules['max_length']) {
                $errors[$field] = ucfirst($field) . ' must be less than ' . $rules['max_length'] . ' characters';
            }

            // Minimum value validation for numbers
            if (isset($rules['min_value']) && is_numeric($_POST[$field]) && $_POST[$field] < $rules['min_value']) {
                $errors[$field] = ucfirst($field) . ' must be at least ' . $rules['min_value'];
            }

            // Maximum value validation for numbers
            if (isset($rules['max_value']) && is_numeric($_POST[$field]) && $_POST[$field] > $rules['max_value']) {
                $errors[$field] = ucfirst($field) . ' must be less than ' . $rules['max_value'];
            }

            // Equal value validation for strings
            if (isset($rules['equal']) && $_POST[$field] !== $rules['equal']) {
                $errors[$field] = ucfirst($field) . ' must be equal to ' . $rules['equal'];
            }

            // Equal value validation for numbers
            if (isset($rules['equal_value']) && is_numeric($_POST[$field]) && $_POST[$field] != $rules['equal_value']) {
                $errors[$field] = ucfirst($field) . ' must be equal to ' . $rules['equal_value'];
            }

            // Email format validation
            if (isset($rules['email']) && !filter_var($_POST[$field], FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = 'Invalid email format';
            }

            // Match value validation
            if (isset($rules['match']) && $_POST[$field] !== $_POST[$rules['match']]) {
                $errors[$field] = ucfirst($field) . ' must match ' . $rules['match'];
            }
        }
    }

    public static function generateCsrfToken()
    {
        self::startSession();

        $csrfToken = bin2hex(random_bytes(35));

        $_SESSION['csrf_token'] = $csrfToken;

        return $csrfToken;
    }

    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function checkCsrfToken()
    {

        self::startSession();

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // CSRF token is invalid, handle error (e.g., log attempt, display error message)
            // show an error message
            echo '<p >Error: invalid form submission. CSRF token mismatch</p>';
            // return 405 http status code
            header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
            exit();
        }
    }
}
