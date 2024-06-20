<?php

// Function to send an email after product creation
function send_product_creation_email($person_id, $pdf_url)
{
    // Get the person's email from post meta
    //$person_email = get_post_meta($person_id, 'person_email', true);
    $person_email = 'tarikul47@gmail.com';

    if (!$person_email) {
        return; // Email not set, do nothing
    }

    $subject = 'Your Product Has Been Created';
    $message = 'Hello, a new product has been created for you. You can download your PDF here: ' . $pdf_url;
    $headers = array('Content-Type: text/html; charset=UTF-8');

    // Send the email
    wp_mail($person_email, $subject, $message, $headers);
}