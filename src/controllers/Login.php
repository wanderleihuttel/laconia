<?php

class Login extends Controller
{
    public $page_title = 'Login';
    public $title = SITE_NAME;
    public $message;

    public function show() {
        if (isset($_POST['login'])) {
            $database = new Database();
            
            // Get form values
            $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
            $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;
            $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
            
            // Retrieve the user account information for the given username.
            $database->query("SELECT id, username, password FROM users WHERE username = :username");
            $database->bind(':username', $username);
            
            $user = $database->result();
            
            // Could not find a user with that username
            if ($user === false) {
                $this->message = 'Incorrect username / password combination! <a href="/">Back</a>';
            } else {
        
                // User account found.
                $validPassword = password_verify($passwordAttempt, $user['password']);
                
                if ($validPassword) {
                    
                    // User login session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['logged_in'] = time();
                    
                    header('Location: /');
                    exit;
                } else {
                    $this->message = 'Incorrect username / password combination!';
                }
            }
        }
        $this->view('login');
    }
}