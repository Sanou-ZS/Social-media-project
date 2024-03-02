<?php
/*
    POSTS using SQL
*/

// Enqueue the CSS file
function admin_page_styles2()
{
    // wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
    // wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_style('admin-page-styles2', plugin_dir_url(__FILE__) . 'post.css');
}
add_action('wp_enqueue_scripts', 'admin_page_styles2');
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
// Function to add a new post
function add_new_post()
{
    // Check if form is submitted
    if (isset($_POST['submit_post'])) {
        // Get the post content from the form
        $post_content = $_POST['post_content'];

        // Set up the post data
        $post_data = array(
            'post_title' => 'Your Post Title',
            'post_content' => $post_content,
            'post_status' => 'publish',
            'post_author' => 1, // Change this to the ID of the author
            'post_type' => 'post' // Change this to the post type if not a standard post
        );

        // Insert the post into the database
        $post_id = wp_insert_post($post_data);

        // Check if the post was successfully inserted
        if ($post_id) {
            wp_redirect(get_permalink());

        } else {
            echo 'Failed to add post.';
        }

    }
    ?>
    <!-- HTML Form to add new post -->
    <form method="post" action="" style="background-color:#cfeafc;">
        <?php $destination_page_url = home_url('/sql-comments');
        echo ' <a href="' . esc_url($destination_page_url) . '"
            style="display: inline-block; padding: 10px 20px; font-size: 16px; text-align: center; text-decoration: none; background-color: #3498db; color: #fff; border-radius: 5px; transition: background-color 0.3s ease;"
            onmouseover="this.style.backgroundColor=\'#2980b9\'" onmouseout="this.style.backgroundColor=\'#3498db\'">show
            comments</a>'; ?>

        <label for="post_content" id="l">Post Content:</label><br>
        <textarea name="post_content" id="post_content" rows="5" cols="50"></textarea><br>
        <button type="submit" name="submit_post" id="b">Add Post</button>
    </form>
    <?php
}

// Function to display posts
function display_posts()
{
    // Query posts
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1, // Retrieve all posts
    );

    $posts = get_posts($args);

    // Check if there are posts
    if ($posts) {
        // echo '<h2>Posts</h2>';
        echo '<h2 style="margin-top: 90px;margin-buttom: 40px;color: #1a00cc;">Your Existing Posts</h2>';
        echo '<div class="post-container" style="width: 80%; ">';
        echo '<ul class="post" >';
        foreach ($posts as $post) {
            echo '<div style="border-bottom: 2px solid #aaa; padding: 10px;">';
            echo '<p style="font-size: 20px;">' . $post->post_content, '<br>', $post->post_date . '</p>';

            // Comment input (initially hidden)
            echo '<form id="addPostForm" method="POST" class="comment-form" style="height:40%;">';
            echo '<label for="commentContent">Comment:</label>';
            echo '<textarea id="commentContent" name="commentContent" required></textarea>';
            echo '<input type="hidden" name="postid" value="' . esc_attr($post->ID) . '">';
            echo '<input type="submit" name="add_json_comment" value="Add Comment">';
            echo '</form>';
            echo '</div>';

        }
        echo '</ul>';
        echo '</div>';
    } else {
        echo '<p>No posts found.</p>';
    }


    //add comment
    if (isset($_POST['add_json_comment'])) {
        $comment_content = $_POST['commentContent'];
        // Set up the post data
        $comment_data = array(
            'comment_ID' => 'Your Post Title',
            'comment_post_id' => $comment_content,
            'comment_content' => 'publish'
        );

        // Insert the post into the database
        $comment_id = wp_insert_comment($comment_data);
        if ($comment_id) {
            echo 'comment added successfully with ID: ' . $comment_id;
        } else {
            echo 'Failed to add comment.';
        }
    }

}

// Function to display users and handle user actions
function admin_formss()
{
    global $wpdb;

    ob_start();

    // Call the function to add a new post
    add_new_post();

    // Display posts
    display_posts();

    return ob_get_clean();
}

// Register the shortcode
add_shortcode('post', 'admin_formss');


function display_custom_posts()
{
    // Query posts
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1, // Retrieve all posts
    );

    $custom_posts = get_posts($args);

    // Check if there are posts
    if ($custom_posts) {
        echo '<h2 style="margin-top: 90px;margin-buttom: 40px;color: #1a00cc;">Your Existing Custom Posts</h2>';
        echo '<div class="post-container" style="width: 80%; ">';
        echo '<ul class="post" >';
        foreach ($custom_posts as $custom_post) {
            echo '<div style="border-bottom: 2px solid #aaa; padding: 10px;">';
            echo '<p style="font-size: 20px;">' . $custom_post->post_content, '<br>', $custom_post->post_date . '</p>';

            // Display comments for each post
            display_custom_comments($custom_post->ID);

        }
        echo '</ul>';
        echo '</div>';
    } else {
        echo '<p>No custom posts found.</p>';
    }

    // Add comment
    if (isset($_POST['add_custom_comment'])) {
        $comment_content = sanitize_text_field($_POST['commentContent']);
        $post_id = absint($_POST['postid']);

        $comment_data = array(
            'comment_post_ID' => $post_id,
            'comment_content' => $comment_content,
        );

        // Insert the comment into the database
        $comment_id = wp_insert_comment($comment_data);

        if ($comment_id) {
            echo 'Comment added successfully with ID: ' . $comment_id;
        } else {
            echo 'Failed to add comment.';
        }
    }
}

// Function to display comments for a specific post
function display_custom_comments($post_id)
{
    $custom_comments = get_comments(array('post_id' => $post_id));

    if ($custom_comments) {
        echo '<ul class="comment-list">';
        foreach ($custom_comments as $custom_comment) {
            echo '<li class="comment">';
            echo '<p>' . esc_html($custom_comment->comment_content) . '</p>';
            echo '</li>';
        }
        echo '</ul>';
    }
}

// Register the shortcode
add_shortcode('display_customs_posts', 'display_custom_posts');
