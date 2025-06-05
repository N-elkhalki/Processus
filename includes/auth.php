<?php
function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}
// Password hashing function to mimic WordPress
function hashPassword($password) {
    // WordPress uses bcrypt with a cost of 10
    return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
}

// Password verification function to check against the hash
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// WordPress-like password checking (mimicking wp_check_password)
function wp_check_password($password, $hash, $user_id = null) {
    // Check if the password matches using bcrypt hash (WordPress uses bcrypt)
    if (password_verify($password, $hash)) {
        return true;
    }
    return false;
}

?>
