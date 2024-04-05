<?php
class User
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUser()
    {
        $sql = "SELECT * FROM users";
        return $this->db->select($sql);
    }

    public function createUser($user)
    {

        $conn = $this->db->getConnection();

        $sql = "INSERT INTO users (name, email, address) VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $user['name'], $user['email'], $user['address']);
        $success = $stmt->execute();


        $stmt->close();
        $conn->close();

        if ($success) {
            return true;
        }

        return false;
    }


    public function findUserById($id)
    {
        $conn = $this->db->getConnection();


        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id); // 'i' indicates integer type for the parameter
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        return $user;
    }

    public function updateUser($id, $name, $mail, $add)
    {
        $conn = $this->db->getConnection();

        $sql = "UPDATE users SET name = ?, email = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $mail, $add, $id);

        // Execute the statement
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();

        // Close the connection
        $conn->close();

        // Return the result
        return $result;
    }

    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = '$id'";
        $result = $this->db->execute($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function isExistEmail($email)
    {
        // Connect to the database (adjust this according to your database configuration)
        $conn = $this->db->getConnection();


        // Prepare SQL statement to check for user with given email
        $sql = "SELECT COUNT(*) AS count FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);

        // Execute SQL statement
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        // Fetch row
        $row = $result->fetch_assoc();

        // Return true if user with given email exists, false otherwise
        return $row['count'] > 0 ? true : false;
    }

    public function register($userInfor)
    {
        $email = $userInfor['email'];
        $name = $userInfor['name'];
        $password = $userInfor['password'];

        // Connect to your database (adjust this according to your database configuration)
        $conn = $this->db->getConnection();

        if ($this->isExistEmail($email)) {
            return "exist email";
        }


        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to insert new user record
        $sql = "INSERT INTO users (name,email, password) VALUES (?,?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        // Execute SQL statement
        $result = $stmt->execute();

        // Close database connection
        $stmt->close();
        $conn->close();

        // Return true on success, false on failure
        return $result;
    }

    public function login($userInfor)
    {
        $email = $userInfor['email'];
        $password = $userInfor['password'];
        $rememberMe = $userInfor['remember'];


        // Connect to the database (adjust this according to your database configuration)
        $conn = $this->db->getConnection();

        // Prepare SQL statement to retrieve user record
        $sql = "SELECT id,name, email, password, failed_attempts FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);

        // Execute SQL statement
        $stmt->execute();

        // Get result set
        $result = $stmt->get_result();




        // Check if user exists
        if ($result->num_rows == 1) {
            // Fetch user record
            $user = $result->fetch_assoc();


            if ($this->isUserLocked($email)) {
                return "locked";
            };
            // Verify password
            if (password_verify($password, $user['password'])) {
                $this->resetFailedAttempts($email, $conn);

                // Password is correct, start session and set user ID
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];

                if ($rememberMe === "on") {
                    // Generate a random remember token
                    $token = bin2hex(random_bytes(16));

                    // Store the remember token in the database
                    $this->updateRememberToken($user['id'], $token);

                    // Set the remember token as a cookie
                    setcookie('remember_me', $user['id'] . ':' . $token, time() + (86400 * 30), '/'); // 30 days expiry
                }

                return true;
            } else {
                $this->updateFailedAttempts($email, $user['failed_attempts'] ?? 0, $conn);
            }
        }

        // Close database connection and return failure
        $stmt->close();
        $conn->close();
        return false;
    }

    private function updateFailedAttempts($email, $currentAttempts, $conn)
    {
        $maxAttempts = 5; // Set maximum allowed failed attempts
        $lockoutPeriod = 300; // Set lockout period in seconds (e.g., 5 minutes)

        // Update failed attempts and last attempt timestamp
        $currentAttempts++;
        $currentTime = date('Y-m-d H:i:s');
        $sql = "UPDATE users SET failed_attempts = ?, last_attempt = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $currentAttempts, $currentTime, $email);
        $stmt->execute();

        // If maximum attempts reached, lock the account
        if ($currentAttempts >= $maxAttempts) {
            $lockoutTime = strtotime($currentTime) + $lockoutPeriod;
            $sql = "UPDATE users SET locked_until = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $lockoutTime, $email);
            $stmt->execute();
        }
    }

    public function isUserLocked($email)
    {
        // Connect to the database
        $conn = $this->db->getConnection();

        // Prepare SQL statement to retrieve last_attempt and locked_until for the user
        $sql = "SELECT locked_until FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);

        // Execute SQL statement
        $stmt->execute();

        // Get result set
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows == 1) {
            // Fetch user record
            $user = $result->fetch_assoc();

            // Check if the user is locked based on last_attempt and locked_until
            $lockedUntil = $user['locked_until'];
            $currentTime = time();

            if ($lockedUntil !== null && $currentTime < $lockedUntil) {
                // If the locked_until timestamp is set and hasn't passed yet,
                // consider the user as locked
                return true;
            }
        }

        // Close database connection and return false (user is not locked)

        return false;
    }

    private function resetFailedAttempts($email, $conn)
    {
        // Reset failed attempts and last attempt timestamp
        $sql = "UPDATE users SET failed_attempts = 0, last_attempt = NULL WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
    }

    public function updateRememberToken($user_id, $token)
    {
        // Connect to the database
        $conn = $this->db->getConnection();

        // Prepare SQL statement to update the remember_token field
        $sql = "UPDATE users SET remember_token = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $token, $user_id);

        // Execute SQL statement
        $stmt->execute();
    }

    public function clearSession()
    {
        Helper::startSession();

        $this->updateRememberToken($_SESSION['user_id'], "");
        setcookie('remember_me', '', time() - 3600, '/');
        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session.
        session_destroy();
    }
}
