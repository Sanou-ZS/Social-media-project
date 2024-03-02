<?php
/*
    Admin JSON 
*/

// Enqueue the CSS file
// $userID = get_user_meta(get_current_user_id(), 'custom_user_id', true);
// echo "User ID on les-posts page: $userID";
function admin_page_styles()
{
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    // wp_enqueue_style('admin-page-styles', plugin_dir_url(__FILE__) . 'admin.css');
}
add_action('wp_enqueue_scripts', 'admin_page_styles');

$valid_user = false;

?>
<style>
    /* style.css */

    /* General Styles */
    .custom-button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        text-align: center;
        text-decoration: none;
        background-color: #3498db;
        color: #fff;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .custom-button:hover {
        background-color: #2980b9;
    }

    .b {
        margin-left: 5cm;
    }

    p {
        font-size: 30px;
    }

    form {
        height: 50%;
    }

    #addPostForm {
        display: flex;
        flex-direction: column;
    }

    #postContent {
        margin-bottom: 10px;
        padding: 8px;
        width: 100%;
        height: 50%;
        font-size: 1em;
        border: 2px solid #0d88e7;
    }

    #addPostForm input[type="submit"] {
        background-color: #0d88e7;
        color: white;
        padding: 10px 30px;
        border-radius: 10px;
        cursor: pointer;
    }

    /* Social Post Container Styles */
    .post-container {
        max-width: 600px;
        margin: 0 auto;
    }

    /* Styles for Each Social Post */
    .social-post {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 20px;
    }

    .post-content {
        font-size: 1.1em;
        margin-bottom: 10px;
    }

    .commentContent {
        border: 1px solid blue;
        padding: 10px;
        margin-bottom: 5px;
    }

    /* Style for the comment form */
    .comment-form {
        background: linear-gradient(90deg, rgba(3, 115, 134, 1) -3131%, rgba(182, 89, 183, 1) -2003%, rgba(8, 41, 206, 1) -982%, rgba(219, 231, 255, 1) 0%, rgba(146, 202, 247, 1) 47%, rgba(108, 126, 244, 1) 99%);
        display: flex;
        flex-direction: column;
        margin-top: 10px;
        /* Adjust as needed */
    }

    .comment-form label {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .comment-form textarea {
        padding: 8px;
        font-size: 14px;
        border: 2px solid black;
        margin-bottom: 10px;
        width: 90%;
    }

    .comment-form input[type="submit"] {
        background-color: #0497db;
        color: white;
        padding: 10px;
        width: 40%;
        border-color: blue;
        border-radius: 10px;
        cursor: pointer;
    }

    .comment-form input[type="submit"]:hover {
        background-color: #0371a3;
    }
</style>


<?php

// Shortcode to display the styled admin form
function admin_form()
{
    if (isset($_SESSION['json_user_admin']) && $_SESSION['json_user_admin'] != "true") {
        return "<p>You don't have permissions to access this page</p>";
    }
    $filename = PROJECT_PATH . 'json-project/users.json';
    $users_data = json_decode(file_get_contents($filename), true);

    ob_start();

    // Check for user deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
        error_log('Delete user submit');
        $delete_user_id = sanitize_text_field($_POST['delete_user_id']);

        // Read existing JSON data
        $jsonContent = file_get_contents($filename);

        // Decode JSON data into PHP array
        $data = json_decode($jsonContent, true);

        // Find and remove the user with the specified ID
        foreach ($data['users'] as $key => $user) {
            if ($user['userId'] == $delete_user_id) {
                unset($data['users'][$key]);
                break; // Assuming user IDs are unique
            }
        }

        // Encode the updated array back into JSON
        $updatedJson = json_encode($data, JSON_PRETTY_PRINT);

        // Write the new JSON data back to the file
        file_put_contents($filename, $updatedJson);

        // Redirect to a different page to avoid form resubmission
        wp_redirect(get_permalink());
        exit;
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Create an array with the new user information
        error_log('Create or edit user submit');
        $newUserInfo = array(
            "userId" => sanitize_text_field($_POST['userId']),
            "username" => sanitize_text_field($_POST['userName']),
            "fullName" => sanitize_text_field($_POST['fullName']),
            "email" => sanitize_text_field($_POST['email']),
            "password" => sanitize_text_field($_POST['password']),
            "birthdate" => sanitize_text_field($_POST['birthday']),
            "admin" => "false",
            "location" => sanitize_text_field($_POST['location']),
        );


        // Read existing JSON data

        $jsonContent = file_get_contents($filename);

        // Decode JSON data into PHP array
        $data = json_decode($jsonContent, true);

        if (isset($_POST['add_user'])) {
            // Add new information to the PHP array
            $data['users'][] = $newUserInfo;
        } else if (isset($_POST['edit_user'])) {
            foreach ($data['users'] as $key => $user) {
                if ($user['userId'] == sanitize_text_field($_POST['edit_user'])) {
                    $data['users'][$key] = $newUserInfo;
                    break; // Assuming user IDs are unique
                }
            }
        }
        // Encode the updated array back into JSON
        $updatedJson = json_encode($data, JSON_PRETTY_PRINT);

        // Write the new JSON data back to the file
        file_put_contents($filename, $updatedJson);

        // Redirect to a different page to avoid form resubmission
        wp_redirect(get_permalink());
        exit;

    }

    ?>

    <form action="" method="post" class="login-form ">
        <a href="" class="btn btn-primary" id="showAddForm">add</a>

        <div id="addForm" style="display: none; ">

            <label for="userName">user id:</label>
            <input type="text" name="userId" id="userId" placeholder="user id" required />

            <label for="userName">user name:</label>
            <input type="text" name="userName" id="userName" placeholder="user name" />

            <label for="fullName">full name:</label>
            <input type="text" name="fullName" id="fullName" placeholder="full name" />

            <label for="email">email:</label>
            <input type="text" name="email" id="email" placeholder="email" required />

            <label for="password">password:</label>
            <input type="text" name="password" id="password" placeholder="password" required />

            <label for="birthday">birthday:</label>
            <input type="text" name="birthday" id="birthday" placeholder="birthday" />

            <label for="location">location:</label>
            <input type="text" name="location" id="location" placeholder="location" />

            <button type="submit" id="save_btn" name="add_user" class="btn btn-success btn-lg" value="">Save</button>
        </div>
        <table class="table table-bordered table-striped" style="margin-top:50px;">
            <thead>
                <th>user name</th>
                <th>full name</th>
                <th>email</th>
                <th>password</th>
                <th>birthday</th>
                <th>location</th>
                <th>action</th>
            </thead>
            <tbody>
                <?php foreach ($users_data['users'] as $user): ?>
                    <tr data-id="<?php echo esc_attr($user['userId']); ?>">
                        <td>
                            <?php echo $user['username'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $user['fullName'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $user['email'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $user['password'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $user['birthdate'] ?? ''; ?>
                        </td>
                        <td>
                            <?php echo $user['location'] ?? ''; ?>
                        </td>
                        <td>
                            <a href='#addForm' class='btn btn-success btn-sm showEditForm'>Edit</a>
                            <form method="post" action=""
                                style="background-color:transparent; display:inline; padding:0; margin:0;">
                                <button type="submit" class="btn btn-danger btn-sm"
                                    value="<?php echo esc_attr($user['userId']); ?>" name="delete_user_id"
                                    style="margin-top: 0;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>

    <script>     document.addEventListener('DOMContentLoaded', function () {
            var showAddFormButton = document.getElementById('showAddForm'); var addForm = document.getElementById('addForm'); var showEditFormButtons = document.querySelectorAll('.showEditForm'); showAddFormButton.addEventListener('click', function (event) { event.preventDefault(); addForm.style.display = addForm.style.display === 'none' ? 'block' : 'none'; document.getElementById('userId').value = ''; document.getElementById('userName').value = ''; document.getElementById('fullName').value = ''; document.getElementById('email').value = ''; document.getElementById('password').value = ''; document.getElementById('birthday').value = ''; document.getElementById('location').value = ''; document.getElementById('save_btn').name = 'add_user'; }); showEditFormButtons.forEach(function (button) {
                button.addEventListener('click', function (event) {                 // event.preventDefault();                 // console.log(button.closest('tr').getAttribute('data-id'));                 var trElement = button.closest('tr');                 var id = trElement.getAttribute('data-id');                 var tdElements = trElement.getElementsByTagName('td');                 var user_name = tdElements[0].innerText;                 var full_name = tdElements[1].innerText;                 var email = tdElements[2].innerText;                 var password = tdElements[3].innerText;                 var birthday = tdElements[4].innerText;                 var location = tdElements[5].innerText;                 console.log(full_name);                 document.getElementById('userId').value = id;                 document.getElementById('userName').value = user_name;                 document.getElementById('fullName').value = full_name;                 document.getElementById('email').value = email;                 document.getElementById('password').value = password;                 document.getElementById('birthday').value = birthday;                 document.getElementById('location').value = location;                 document.getElementById('save_btn').name = 'edit_user';                 document.getElementById('save_btn').value = id;                 addForm.style.display = 'block';
                });
            });
        });
    </script>
    <?php
    return ob_get_clean();
}

// Register the shortcode
add_shortcode('Admin', 'admin_form');

//shortcode for displaying posts and add post button
function display_posts_and_button_shortcode()
{
    if (!isset($_SESSION['json_user_id'])) {
        return "You don't have permission to access this page";
    }
    if (isset($_SESSION['json_user_admin']) && $_SESSION['json_user_admin'] == "true") {
        return "You are administrator you can just manage users";
    }
    ob_start(); // Start output buffering
    $destination_page_url = home_url('/show-comments');

    // Display "Go to Another Page" button with the dynamically generated URL
    echo '<a href="' . esc_url($destination_page_url) . '" style="display: inline-block; padding: 10px 20px; font-size: 16px; text-align: center; text-decoration: none; background-color: #3498db; color: #fff; border-radius: 5px; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor=\'#2980b9\'" onmouseout="this.style.backgroundColor=\'#3498db\'">show comments</a>';

    // Display button to add new post
    echo '<h2 style="color: #1a00cc;">Add New Post</h2>';
    echo '<form id="addPostForm" method="POST" style="height: 50%;">';
    echo '<label for="postContent" style="font-size:25px;font-weight:bold;">Content:</label>';
    echo '<textarea id="postContent" name="postContent" required></textarea>';
    echo '<input type="submit" value="Add Post" name="add_json_post ">';
    echo '</form>';

    $json_path = PROJECT_PATH . 'json-project/users.json';

    // Read existing data from the JSON file
    $json_data = file_get_contents($json_path);
    $posts = json_decode($json_data, true);


    if (isset($_POST['add_json_post'])) {
        $post_content = wp_kses_post($_POST['postContent']);
        // Create a new post
        $postid = rand(1, 100);
        $new_post = array(
            "postId" => $postid,
            "userId" => $_SESSION['json_user_id'],
            "content" => $post_content,
            "comments" => [],
            "createdAt" => date("Y-m-d H:i:s"),
        );

        $posts['posts'][] = $new_post;
        // Encode the updated array back into JSON
        $updatedJson = json_encode($posts, JSON_PRETTY_PRINT);

        // Write the new JSON data back to the file
        file_put_contents($json_path, $updatedJson);

        wp_redirect(get_permalink());
        exit;
    }
    echo '<h2 style="margin-top: 90px;margin-buttom: 30px;color: #1a00cc;">Your Existing Posts</h2>';
    echo '<div class="post-container" style="width: 80%; ">';
    foreach ($posts['posts'] as $post):
        if ($post['userId'] == $_SESSION['json_user_id']) {
            echo '<div style="border-bottom: 2px solid #aaa; padding: 10px;">';
            echo '<p style="font-size: 30px;">' . esc_html($post['content'] ?? '') . '</p>';

            // Comment input (initially hidden)
            echo '<form id="addPostForm" method="POST" class="comment-form" style="height:40%;">';
            echo '<label for="commentContent">Comment:</label>';
            echo '<textarea id="commentContent" name="commentContent" required></textarea>';
            echo '<input type="hidden" name="postid" value="' . esc_attr($post['postId']) . '">';
            echo '<input type="submit" name="add_json_comment" value="Add Comment">';
            echo '</form>';


            echo '</div>';
        }




    endforeach;
    echo '</div>';

    if (isset($_POST['add_json_comment'])) {
        $comment_content = wp_kses_post($_POST['commentContent']);
        // Create a new post
        $new_comment = array(
            "commentId" => rand(1, 100),
            "userId" => $_SESSION['json_user_id'],
            "postId" => $_POST['postid'],
            "content" => $comment_content,
            "createdAt" => date("Y-m-d H:i:s"),
        );

        $posts['comments'][] = $new_comment;
        // Encode the updated array back into JSON
        $updatedJson = json_encode($posts, JSON_PRETTY_PRINT);

        // Write the new JSON data back to the file
        file_put_contents($json_path, $updatedJson);
        wp_redirect(get_permalink());
        exit;

    }


    return ob_get_clean(); // Return buffered content
}


// Register the shortcode
add_shortcode('display_posts_and_button', 'display_posts_and_button_shortcode');

add_action('init', 'start_session', 1);
function start_session()
{
    if (!session_id()) {
        session_start();
    }
}



function display_comments_and_button_shortcode()
{
    if (!isset($_SESSION['json_user_id'])) {
        return "You don't have permission to access this page";
    }
    if (isset($_SESSION['json_user_admin']) && $_SESSION['json_user_admin'] == "true") {
        return "You are administrator you can just manage users";
    }
    ob_start(); // Start output buffering

    $json_path = PROJECT_PATH . 'json-project/users.json';

    // Read existing data from the JSON file
    $json_data = file_get_contents($json_path);
    $posts = json_decode($json_data, true);

    echo '<h2 style="margin-top: 90px;margin-buttom: 30px;color: #1a00cc;">Your Existing Posts</h2>';
    echo '<div class="post-container" style="width: 80%;">';
    foreach ($posts['posts'] as $post):
        if ($post['userId'] == $_SESSION['json_user_id']) {
            echo '<div style="border: 2px solid #ddd; border-radius: 10px; padding: 20px; margin-bottom: 20px;background-color: #ced9ee;">';
            echo '<p style="font-size: 18px; margin-bottom: 10px;">' . esc_html($post['content'] ?? '') . '</p>';
            $postid = $post['postId'];

            // Display existing comments
            if (!empty($posts['comments'])) {
                echo '<div class="comment-list" style="margin-top: 20px;">';

                foreach ($posts['comments'] as $comment):
                    if ($comment['userId'] == $_SESSION['json_user_id'] && $comment['postId'] == strval($postid)) {
                        echo '<div class="comment" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; margin-bottom: 10px; background-color: #eee;">';
                        echo '<p style="font-size: 14px; margin-bottom: 5px;">' . esc_html($comment['content'] ?? '') . '</p>';
                        echo '</div>';
                    }
                endforeach;
                echo '</div>';
            }
            echo '</div>';
        }
    endforeach;

    echo '</div>';


    return ob_get_clean(); // Return buffered content
}


// Register the shortcode
add_shortcode('display_comments_and_button', 'display_comments_and_button_shortcode');
