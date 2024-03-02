<?php
/*
    JSON Login form and scripts
*/

// Enqueue the CSS file

// Enqueue the CSS file
function login_page_styles()
{
    wp_enqueue_style('login-page-styles', plugin_dir_url(__FILE__) . 'login-page.css', array(), true, 'all');
}
add_action('wp_enqueue_scripts', 'login_page_styles', 7);

// add_action('wp_enqueue_scripts', 'login_page_styles');

// Shortcode to display the styled login form
function login_page_form()
{
    ob_start();

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_email']) && isset($_POST['login_password'])) {

        // Sanitize and validate input
        $email = sanitize_email($_POST['login_email']);
        $password = sanitize_text_field($_POST['login_password']);

        // Load user data from JSON file
        $users_data = json_decode(file_get_contents(PROJECT_PATH . 'json-project/users.json'), true);

        // Check if user credentials are valid
        $valid_user = false;

        foreach ($users_data['users'] as $user) {
            if ($user['email'] === $email && $user['password'] === $password) {
                $valid_user = true;
                break;
            }
        }

        // Process login based on validation result
        // On the login page or where you handle the login logic
        if (!$valid_user) {
            echo '<p class="error-message" style="color:red; font-size:20px; font-weight:bold;">Login failed. Please try again.</p>';
        } else {
            // // Assuming $user['userId'] contains the user ID
            // update_user_meta($user['userId'], 'custom_user_id', $user['userId']);

            // // Log the user in
            // wp_set_auth_cookie($user['userId']);

            $_SESSION['json_user_id'] = $user['userId'];
            $_SESSION['json_user_admin'] = $user['admin'];

            if ($user['admin'] === "false") {
                // Redirect to 'les-posts' page
                wp_redirect(home_url('/json-les-posts'));
            } else {
                // Redirect to 'admin' page
                wp_redirect(home_url('/json-admin'));
            }
            exit();
        }
    }

    ?>
    <form action="" method="post" class="login-form">
        <div class="container" id="container" style="background-color:#fff box-shadow: 0px 0px 44px 0px rgba(14,0,142,0.7);
-webkit-box-shadow: 0px 0px 44px 0px rgba(14,0,142,0.7);
-moz-box-shadow: 0px 0px 44px 0px rgba(14,0,142,0.7);width:650px">
            <div class="form-container sign-in-container">
                <h1>Login</h1>
                <input type="email" name="login_email" placeholder="Email" style="width:80%;margin:8px;" required />
                <input type="password" name="login_password" placeholder="Password" style="width:80%;margin:8px 0 25px 0;"
                    required />
                <button type=" submit" style="border-radius:10px;">Connect</button>
            </div>
            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-right">
                        <h1>Welcome !</h1>
                        <p>Connect to your social media</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php

    return ob_get_clean();
}

// Register the shortcode
add_shortcode('json_login_form', 'login_page_form');
