# WordPress Installation and Configuration Guide

This guide outlines the steps to set up a WordPress website using XAMPP, install the Twenty Twenty Four theme, activate the Social Media Project plugin, and create two projects for managing users and posts.

## Installation Steps:

1. **Install XAMPP:**

   - Download and install XAMPP from [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).
   - Follow the installation instructions for your operating system.

2. **Install WordPress:**

   - Download the latest version of WordPress from [https://wordpress.org/download/](https://wordpress.org/download/).
   - Extract the WordPress files to the `htdocs` directory of your XAMPP installation.
   - Create a new MySQL database for WordPress using phpMyAdmin.

3. **Configure WordPress:**

   - Navigate to `http://localhost/wordpress` in your web browser.
   - Follow the on-screen instructions to complete the WordPress installation.
   - Set up your WordPress admin account.

4. **Install Twenty Twenty Four Theme:**

   - Go to the WordPress admin dashboard.
   - Navigate to "Appearance" > "Themes" > "Add New".
   - Search for "Twenty Twenty Four" and install the theme.
   - Activate the Twenty Twenty Four theme.

5. **Activate Social Media project Plugin:**

   - In the WordPress admin dashboard, go to "Plugins" > "Add New".
   - Go to "Upload Plugin" (.zip).
   - Search for "Social Media Project" and install the plugin.
   - Activate the Social Media Project plugin.

6. **Add Pages:**

   1. **Structured Data with MySQL Database:**

      - Create four pages:
      - Login (title:sql login , shortcode:[custom_login_shortcode]).
      - Admin (title:sql admin , shortcode:[Adminn]).
      - Posts (title:sql posts , shortcode:[post]).
      - Show comments (title:sql comments , shortcode:[display_customs_posts]).

   2. **Semi-Structured with JSON File:**
      - Create four pages:
      - Login (title:json login , shortcode:[json_login_form]).
      - Admin (title:json admin , shortcode:[Admin]).
      - Posts (title:json posts , shortcode:[display_posts_and_button]).
      - Show comments (title:json comments , shortcode:[display_comments_and_button]).

7. **Page URLs:**

   - Sql Login: `http://localhost/wordpress/login-sql`
   - Sql Admin: `http://localhost/wordpress/sql-admin`
   - Sql Posts: `http://localhost/wordpress/posts`
   - Sql Comments: `http://localhost/wordpress/sql-comments`

   - Json Login: `http://localhost/wordpress`
   - Json Admin: `http://localhost/wordpress/json-admin`
   - Json Posts: `http://localhost/wordpress/json-les-posts`
   - Json Comments: `http://localhost/wordpress/show-comments`

## Usage:

- **Admin Page:**

  - Use this page to perform CRUD operations on users.
  - Only users with 'true' status (administration) can access this page(by login page).

- **Posts Page:**
  - Use this page to read and add posts.
  - Users who sign up via the (login page) with 'false' status can access this page.
  - The page contains a button to show comments associated with user posts called(show comments page).

## Additional Notes:

- Ensure that the necessary permissions are set for accessing admin pages and posting comments.
- Ensure to make the same slugs sets on the URLs pages .
#   S o c i a l - m e d i a - p r o j e c t  
 