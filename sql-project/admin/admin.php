<?php
/* 
    Administration page using SQL
*/

// Enqueue the CSS file
function admin_page_style()
{
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_style('admin-page-styles', plugin_dir_url(__FILE__) . 'admin.css');
}
add_action('wp_enqueue_scripts', 'admin_page_style');

global $wpdb, $table_name;
$table_name = $wpdb->prefix . 'users';

// Function to delete user from custom table
function delete_user_from_custom_table($user_id)
{
    global $wpdb, $table_name;
    // $table_name = $wpdb->prefix . 'users'; // Assuming the table name is 'users'
    $wpdb->delete(
        $table_name,
        array(
            'ID' => $user_id
        )
    );
}

// Function to update user information in custom table
// function update_user_in_custom_table($user_id, $user_data)
// {
//     global $wpdb, $table_name;
//     $wpdb->update(
//         $table_name,
//         $user_data,
//         array('ID' => $user_id)
//     );
// }

// Function to display users and handle user actions
// Function to display users and handle user actions
function admin_forms()
{
    global $wpdb, $table_name;

    ob_start();

    // Handle add user action
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
        $user_data = array(
            'user_login' => $_POST['userName'],
            'user_pass' => $_POST['password'],
            'user_email' => $_POST['email'],
            // 'user_nicename' => $_POST['birthdate'],
            // 'display_name' => $_POST['location'],
            // Add other user fields as needed
        );
        // Insert user data into the database
        $wpdb->insert($table_name, $user_data);
        $user_id = $wpdb->insert_id;

        update_user_meta($user_id, 'location', $_POST['location']);
        update_user_meta($user_id, 'birthdate', $_POST['birthdate']);
        // Redirect to prevent form resubmission
        wp_redirect($_SERVER['REQUEST_URI']);
        exit;
    }

    // Handle edit user action
    // if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user_id'])) {
    //     $user_id_to_edit = intval($_POST['edit_user_id']);
    //     // Redirect to edit user page
    //     wp_redirect(add_query_arg(array('action' => 'edit', 'edit_user_id' => $user_id_to_edit)));
    //     exit;
    // }

    // Handle form submission to update user information
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user_id'])) {
        $user_id_to_update = intval($_POST['edit_user_id']);
        $user_data = array(
            'user_login' => $_POST['userName'],
            'user_pass' => $_POST['password'],
            'user_email' => $_POST['email'],
            // Add other user fields as needed
        );
        $wpdb->update(
            $table_name,
            $user_data,
            array('ID' => $user_id_to_update)
        );
        update_user_meta($user_id_to_update, 'location', $_POST['location']);
        update_user_meta($user_id_to_update, 'birthdate', $_POST['birthdate']);
        // Redirect to prevent form resubmission
        wp_redirect($_SERVER['REQUEST_URI']);
        exit;
    }

    // Display users
    $users = $wpdb->get_results("SELECT * FROM {$table_name}");
    ?>

    <form action="" method="post" class="login-form ">
        <a href="" class="btn btn-primary" id="showAddForm">add</a>
        <div id="addForm" style="display:none ; ">

            <!-- <form method="post" action=""> -->
            <label for="userName">user name:</label>
            <input type="text" name="userName" id="userName" placeholder="user name" required />

            <label for="email">email:</label>
            <input type="email" name="email" id="email" placeholder="email" required />

            <label for="password">password:</label>
            <input type="password" name="password" id="password" placeholder="password" required />

            <label for="birthdate">birthdate:</label>
            <input type="text" name="birthdate" id="birthdate" placeholder="birthdate" required />

            <label for="location">location:</label>
            <input type="text" name="location" id="location" placeholder="location" required />

            <!-- Add other input fields for user data as needed -->
            <button type="submit" id="save_btn" name="add_user" class="btn btn-success btn-lg" value="">Save</button>
            <!-- </form> -->
        </div>
        <?php
        if ($users) {
            echo '<table class="table table-bordered table-striped">';
            echo '<thead><tr><th>ID</th><th>User Name</th><th>Email</th><th>Password</th><th>Birthdate</th><th>Location</th><th>Action</th></tr></thead>';
            echo '<tbody>';
            foreach ($users as $user) {
                echo '<tr data-id="' . $user->ID . '">';
                echo '<td>' . $user->ID . '</td>';
                echo '<td>' . $user->user_login . '</td>';
                echo '<td>' . $user->user_email . '</td>';
                echo '<td>' . $user->user_pass . '</td>';
                echo '<td>' . get_user_meta($user->ID, 'birthdate', true) . '</td>';
                echo '<td>' . get_user_meta($user->ID, 'location', true) . '</td>';
                ?>
                <td>
                    <a href='#addForm' class='btn btn-success btn-sm showEditForm'>Edit</a>
                    <form method="post" action="" style="background-color:transparent; display:inline; padding:0; margin:0;">
                        <input type="hidden" name="delete_user_id" value="<?php echo $user->ID; ?>">
                        <button type="submit" class="btn btn-danger btn-sm" style="margin-top: 0;">Delete</button>
                    </form>

                </td>
                <?php
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No users found.</p>';
        }
        echo '</form>';

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user_id'])) {
            $user_id_to_delete = intval($_POST['delete_user_id']);
            delete_user_from_custom_table($user_id_to_delete);
            // Redirect to prevent form resubmission
            // wp_redirect($_SERVER['REQUEST_URI']);
            wp_redirect(get_permalink());
            exit;
        }

        // Display edit user form
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var showAddFormButton = document.getElementById('showAddForm');
                var addForm = document.getElementById('addForm');
                var showEditFormButtons = document.querySelectorAll('.showEditForm');
                showAddFormButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    addForm.style.display = addForm.style.display === 'none' ? 'block' : 'none';
                    document.getElementById('user_login').value = '';
                    document.getElementById('user_email').value = '';
                    document.getElementById('user_pass').value = '';
                    document.getElementById('user_nicename').value = '';
                    document.getElementById('display_name').value = '';

                });
                showEditFormButtons.forEach(function (button) {
                    button.addEventListener('click', function (event) {
                        // event.preventDefault();
                        // console.log(button.closest('tr').getAttribute('data-id'));
                        var trElement = button.closest('tr');
                        var id = trElement.getAttribute('data-id');
                        var tdElements = trElement.getElementsByTagName('td');
                        var user_name = tdElements[1].innerText;
                        var email = tdElements[2].innerText;
                        var password = tdElements[3].innerText;
                        var birthday = tdElements[4].innerText;
                        var location = tdElements[5].innerText;
                        document.getElementById('userName').value = user_name;
                        document.getElementById('email').value = email;
                        document.getElementById('password').value = password;
                        document.getElementById('birthdate').value = birthday;
                        document.getElementById('location').value = location;
                        document.getElementById('save_btn').name = 'edit_user_id';
                        document.getElementById('save_btn').value = id;
                        addForm.style.display = 'block';

                    });
                });
            });

        </script>


        <?php

        return ob_get_clean();
}


// Register the shortcode
add_shortcode('Adminn', 'admin_forms');

// Add shortcode for displaying posts and add post button
