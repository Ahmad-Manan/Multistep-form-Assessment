<?php
function hello_child_enqueue_styles() {
    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_uri(),
        ['hello-elementor'],
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'hello_child_enqueue_styles');


add_action('wp_ajax_assessment_send_form_email', 'assessment_send_form_email');
add_action('wp_ajax_nopriv_assessment_send_form_email', 'assessment_send_form_email');
add_action('wp_enqueue_scripts', function () {
    wp_localize_script('jquery', 'ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
});

function assessment_send_form_email() {

    $email = sanitize_email($_POST['email'] ?? '');
    $name  = sanitize_text_field($_POST['name'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');

    if (empty($email) || empty($name) || empty($phone)) {
        wp_send_json_error([
            'message' => 'Missing fields'
        ]);
        wp_die();
    }

    if (!is_email($email)) {
        wp_send_json_error([
            'message' => 'Invalid email'
        ]);
        wp_die();
    }

    $to = 'ahmadmanan404@gmail.com';
    $subject = 'New Popup Form Submission';

    $message = "New form submission received:\n\n";
    $message .= "Name: {$name}\n";
    $message .= "Email: {$email}\n";
    $message .= "Phone: {$phone}\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: Assessment <no-reply@alphalinkx.com>', 
        'Reply-To: ' . $name . ' <' . $email . '>'
    ];

    $sent = wp_mail($to, $subject, $message, $headers);

    if ($sent) {
        wp_send_json_success([
            'message' => 'Email sent successfully.'
        ]);
    } else {
        wp_send_json_error([
            'message' => 'Mail failed'
        ]);
    }

    wp_die();
}