<?php

/**
 * Plugin Name:      Tiiza Form
 * Description:       This is a form for multi purpose.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Esther Bassey
 * Text Domain:       tiiza-form
 * 
 */

 if( !defined('ABSPATH') )
{
  die('you cannot be here');
}

if(!class_exists('TiizaForm')) {

  class TiizaForm {

    public function __construct()
    {
      add_action('wp_enqueue_scripts', array($this, 'load_assets'));

      add_shortcode('tiiza-form', array($this, 'display_tiiza_form'));

      add_action('rest_api_init', array($this, 'register_rest_api'));

      add_action('init', array($this, 'create_submission_page'));

      add_action('add_meta_boxes', array($this, 'create_meta_box'));

      add_filter('manage_submission_posts_columns', array($this, 'custom_submission_columns'));

      add_action('manage_submission_posts_custom_column', array($this, 'fill_submission_columns'), 10, 2);

      add_action('admin_init', array($this, 'setup_search'));

    }


    public function setup_search()
    {
      // Only apply filter to submissions page

      global $typenow;

      if ($typenow === 'submission') {

            add_filter('posts_search', array($this, 'submission_search_override'), 10, 2);
      }
    }

    public function submission_search_override($search, $wp_query)
    {
      // Override the submissions page search to include custom meta data

      global $wpdb;

      if ($wp_query->is_main_query() && !empty($wp_query->query['s'])) {
            $sql    = "
              or exists (
                  select * from {$wpdb->postmeta} where post_id={$wpdb->posts}.ID
                  and meta_key in ('first_name', 'middle_name', 'last_name', 'email','phone', 'tracker_id')
                  and meta_value like %s
              )
          ";
            $like   = '%' . $wpdb->esc_like($wp_query->query['s']) . '%';
            $search = preg_replace(
                  "#\({$wpdb->posts}.post_title LIKE [^)]+\)\K#",
                  $wpdb->prepare($sql, $like),
                  $search
            );
      }

      return $search;
    }
    

    public function fill_submission_columns($column, $post_id) 
    {
        switch($column) 
        {
             case 'first_name':
                echo esc_html(get_post_meta($post_id, 'first_name', true));
               break;

               case 'middle_name':
                echo esc_html(get_post_meta($post_id, 'middle_name', true));
               break;

               case 'last_name':
                echo esc_html(get_post_meta($post_id, 'last_name', true));
               break;

              case 'email':
                echo esc_html(get_post_meta($post_id, 'email', true));
                break;

              case 'phone':
                 echo esc_html(get_post_meta($post_id, 'phone', true));
               break;

              case 'address':
                 echo esc_html(get_post_meta($post_id, 'address', true));
               break;

              case 'tracker_id':
                 echo esc_html(get_post_meta($post_id, 'tracker_id', true));
               break;

              case 'category':
                 echo esc_html(get_post_meta($post_id, 'category', true));
               break;

              case 'color':
                 echo esc_html(get_post_meta($post_id, 'color', true));
               break;

              case 'gender':
                 echo esc_html(get_post_meta($post_id, 'gender', true));
               break;

              case 'image':
                 echo esc_html(get_post_meta($post_id, 'image', true));
               break;

              case 'message':
                 echo esc_html(get_post_meta($post_id, 'message', true));
                break;
        }
    }


    public function custom_submission_columns($columns)
    {
       $columns = array(

        'cb' => $columns['cb'],
        'first_name' => __('FirstName', 'textdomain'),
        'middle_name' => __('MiddleName', 'textdomain'),
        'last_name' => __('LastName', 'textdomain'),
        'email' => __('Email', 'textdomain'),
        'phone' => __('Phone', 'textdomain'),
        'address' => __('Address', 'textdomain'),
        'tracker_id' => __('Tracker_ID', 'textdomain'),
        'category' => __('Category', 'textdomain'),
        'color' => __('Color', 'textdomain'),
        'gender' => __('Gender', 'textdomain'),
        'image' => __('Image', 'textdomain'),
        'message' => __('Message', 'textdomain'),
       );

       return $columns;

    }

    public function create_meta_box()
    {
      add_meta_box('tiiza-form-meta-box', 'Contact Information', array($this, 'display_meta_box'), 'submission');
    }

    public function display_meta_box()
    {
      $postmetas = get_post_meta( get_the_ID() );

      echo '<ul>';

        echo '<li><strong>FirstName</strong>:<br />' . get_post_meta( get_the_ID(), 'first_name', true) . '</li>';
        echo '<li><strong>MiddleName</strong>:<br />' . get_post_meta( get_the_ID(), 'middle_name', true) . '</li>';
        echo '<li><strong>LastName</strong>:<br />' . get_post_meta( get_the_ID(), 'last_name', true) . '</li>';
        echo '<li><strong>Email</strong>:<br />' . get_post_meta( get_the_ID(), 'email', true) . '</li>';
        echo '<li><strong>Phone</strong>:<br />' . get_post_meta( get_the_ID(), 'phone', true) . '</li>'; 
        echo '<li><strong>Address</strong>:<br />' . get_post_meta( get_the_ID(), 'address', true) . '</li>';
        echo '<li><strong>Tracker_ID</strong>:<br />' . get_post_meta( get_the_ID(), 'tracker_id', true) . '</li>';
        echo '<li><strong>Category</strong>:<br />' . get_post_meta( get_the_ID(), 'category', true) . '</li>';
        echo '<li><strong>Color</strong>:<br />' . get_post_meta( get_the_ID(), 'color', true) . '</li>';
        echo '<li><strong>Gender</strong>:<br />' . get_post_meta( get_the_ID(), 'gender', true) . '</li>';
        echo '<li><strong>Image</strong>:<br />' . get_post_meta( get_the_ID(), 'image', true) . '</li>';
        echo '<li><strong>Message</strong>:<br />' . get_post_meta( get_the_ID(), 'message', true) . '</li>';

      echo '</ul>';
    }
   

   public function create_submission_page()
    { 
       $args = array(
            'public' => true,
            'has_archive' => true,
            'publicly_queryable' => false,
            'labels' => array(
                'name' => 'Submissions',
                'singular_name' => 'Submission',
                'edit_item' => 'View Submission',
            ),
            'supports' => false,
            'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => false,
            ),
            'map_meta_cap' => true,
        );

        register_post_type('submission', $args);
    } 


    public function load_assets() 
    {
      wp_enqueue_style('tiiza-form-style', plugin_dir_url(__FILE__) . 'css/tiiza-form.css',
      array(),
      '1.0.0',
      'all'
      );
    }

    public function display_tiiza_form() 
    {
      require 'template/tiiza-form.php';
    }



    public function register_rest_api()
    {
      register_rest_route('tiiza-form/v1', 'submit-form', array(
        'methods' => 'POST',
        'callback' => array($this, 'submit_form')
      ));
    }

    public function submit_form($data)
    {

      $params = $data->get_params();

      $field_first_name = sanitize_text_field($data['first_name']);
      $field_middle_name = sanitize_text_field($data['middle_name']);
      $field_last_name = sanitize_text_field($data['last_name']);
      $field_email = sanitize_email($data['email']);
      $field_phone = sanitize_text_field($data['phone']);
      $field_address = sanitize_text_field($data['address']);
      $field_tracker_id = sanitize_text_field($data['tracker_id']);
      $field_category = sanitize_text_field($data['category']);
      $field_color = sanitize_text_field($data['color']);
      $field_message = sanitize_textarea_field($data['message']);

       // validate phone number
      if (!$this->is_valid_phone_number($field_phone)) 
      {
        return new WP_Rest_Response('Invalid phone number', 402);
      }

       // Validate email address format on the server side
    if (!$this->is_valid_email_format($field_email)) {
        return new WP_Rest_Response('Invalid email address format', 401);
    }


      // check if nonce is valid, if not, respond with error
      if( !wp_verify_nonce( $params['_wpnonce'], 'wp_rest') )
      {
        return new WP_Rest_Response('Invalid nonce', 422);
      } 


       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if the file is uploaded successfully
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                $uploadFile = $uploadDir . basename($_FILES['image']['name']);

                // Move the uploaded file to the specified directory
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    // Add image path to the response
                    $response['imagePath'] = $uploadFile;
                } else {
                    // Return error response
                    return new WP_Rest_Response(['success' => false, 'message' => 'Error uploading image'], 500);
                }
            } else {
                // Return error response
                return new WP_Rest_Response(['success' => false, 'message' => 'No image uploaded'], 400);
            }
        } else {
            // Return error response if the request method is not POST
            return new WP_Rest_Response(['success' => false, 'message' => 'Invalid request method'], 405);
        }
        
 
      unset($params['_wpnonce']);
      unset($params['_wp_http_referer']);

      $headers = [];
       

       // Example: Sending email to admin
      $admin_email = get_bloginfo('admin_email');
      $admin_name = get_bloginfo('name');


      $headers[] = "From: {$admin_name} <{$admin_email}>";
      $headers[] = "Reply-To: {$field_first_name} <{$field_email}>";
      $headers[] = "Content-Type: text/html; charset=UTF-8";

      $subject = "New form submission {$field_first_name}";

      $message = '';
      $message .= "<h1>Message has been sent from {$field_first_name}</h1>";


      $postarr = [

           'post_title' => $field_first_name,
           'post_type' => 'submission',
           'post_status' => 'publish'
    ];

  
   $post_id = wp_insert_post($postarr);

   foreach($params as $label => $value)
    {

     switch($label)
    {
        case 'message':
           $value = sanitize_textarea_field($value);
         break;

        case 'email':
           $value = sanitize_email($value);
         break;

        default:
          $value = sanitize_text_field($value);
    }

      add_post_meta($post_id, sanitize_text_field($label), $value);


      $message .= '<strong>' . sanitize_text_field( ucfirst($label) ) . ':</strong>' . $value . '<br />'; 
    }


      wp_mail($admin_email, $subject, $message, $headers);

      return array('message' => 'Form submitted successfully');

  }

  private function is_valid_phone_number($phone)
   {
      // Remove non-numeric characters from the phone number
      $cleaned_phone = preg_replace("/[^0-9]/", "", $phone);

      // Validate the phone number
      if (strlen($cleaned_phone) == 11 && ctype_digit($cleaned_phone)) {
        return true; // Valid phone number
      } else {
         return false; // Invalid phone number
      }

  }

  private function is_valid_email_format($email)
  {
    // Use regular expression to validate email address format
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($pattern, $email);
  } 


}

  new TiizaForm();
}