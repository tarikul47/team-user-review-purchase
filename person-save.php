<?php


/**
 * 1. save_person_meta_boxes()
 * 2. Meat data save as a post in review 
 * 3. get_product_id_by_person()
 * 2. create_woocommerce_product_from_person()
 * 4. generate_pdf_from_person()
 * 5. send_product_creation_email()
 */
function my_save_post_person($post_id, $post, $update)
{
    // Ensure that this hook runs for the 'person' post type
    if ($post->post_type != 'person' || $post->post_status !== 'publish') {
        return;
    }

    // Retrieve existing metadata
    $existing_review_title = get_post_meta($post_id, '_review_title', true);
    $existing_review_content = get_post_meta($post_id, '_review_content', true);
    $existing_review_rating = get_post_meta($post_id, '_review_rating', true);

    // Retrieve new metadata from $_POST
    $new_review_title = isset($_POST['review_title']) ? sanitize_text_field($_POST['review_title']) : '';
    $new_review_content = isset($_POST['review_content']) ? sanitize_text_field($_POST['review_content']) : '';
    $new_review_rating = isset($_POST['review_rating']) ? sanitize_text_field($_POST['review_rating']) : '';

    // Check if any relevant metadata has changed
    $metadata_changed = false;

    if ($existing_review_title !== $new_review_title || $existing_review_content !== $new_review_content || $existing_review_rating !== $new_review_rating) {
        $metadata_changed = true;
    }

    // Always perform these actions regardless of update or new post
    save_metabox_data($post_id, $_POST);
    metabox_data_insert_as_review($post_id, $_POST);

    // Check if a WooCommerce product already exists for this person
    $existing_product_id = get_product_id_by_person($post_id);

    // Create or update WooCommerce product
    $product = create_or_update_woocommerce_product_from_person($post_id, $post, $_POST, $existing_product_id);

    // If a new product was created or metadata has changed, generate downloadable PDF and send email
    if ($product || $metadata_changed) {
        $pdf_url = generate_pdf_from_person($post_id, $post, $_POST, $product);
        if ($pdf_url) {
            send_product_creation_email($post_id, $_POST, $pdf_url);
        } else {
            // Handle PDF generation failure
        }
    } else if ($update) {
        // If post is updated but no new product is created and metadata hasn't changed, perform necessary updates
        // update_person_metadata($post_id, $_POST);
    }
}
add_action('save_post_person', 'my_save_post_person', 10, 3);

