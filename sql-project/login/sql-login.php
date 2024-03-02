<?php
/*
    Login Form and CSS 
*/

// Enqueue the CSS file 
function login_page_style()
{
    wp_enqueue_style('login-page-styles', PROJECT_PATH . 'sql-login.css');
}
add_action('wp_enqueue_scripts', 'login_page_style');

// Shortcode to display the styled login form 
function login_page_forms()
{
    ob_start(); // Start output buffering 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = sanitize_email($_POST['login_email']);
        $password = $_POST['login_password'];

        global $wpdb;

        $table_name = $wpdb->prefix . 'users'; // Use the WordPress table prefix 

        // Retrieve user record based on email 
        $user = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE user_email = %s",
                $email
            )
        );

        if ($user) {
            // Verify the password 
            if ($password === $user->user_pass) {
                if ($user->user_status == 1) {
                    // Password is correct 
                    // Redirect the user to the admins 
                    wp_redirect(home_url('/sql-admin'));
                    // Ensure to stop further execution 
                }
                if ($user->user_status == 0) {
                    // Password is correct 
                    // Redirect the user to the admins 
                    wp_redirect(home_url('/posts'));
                    // Ensure to stop further execution 
                }
            } else {
                // Incorrect password 
                echo '<p class="error-message">Invalid password. Please try again.</p>';

                error_log('Entered Password: ' . $password);
                error_log('Hashed Password from Database: ' . $user->user_pass);

            }
        } else {
            // User not found 
            echo '<p class="error-message">User with this email does not exist. Please try again.</p>';
        }
    }

    // Return the login form HTML 
    ?>
    <form action="" method="post" class="login-form">
        <div class="container" id="container" style="background-color:#fff box-shadow: 0px 0px 44px 0px rgba(14,0,142,0.7);
-webkit-box-shadow: 0px 0px 44px 0px rgba(14,0,142,0.7);
-moz-box-shadow: 0px 0px 44px 0px rgba(14,0,142,0.7);">
            <div class="form-container sign-in-container">
                <h1>Login</h1>
                <input type="email" name="login_email" placeholder="Email" style="width:80%;" required />
                <input type="password" name="login_password" placeholder="Password" style="width:80%;" required />
                <button type=" submit">Connect</button>
            </div>
            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-right">
                        <h1>Hello, User !</h1>
                        <p>Connect to your social media</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php

    // Clear the output buffer and return its contents 
    return ob_get_clean();
}

// Register the shortcode with a more unique tag 
add_shortcode('custom_login_shortcode', 'login_page_forms');
?>