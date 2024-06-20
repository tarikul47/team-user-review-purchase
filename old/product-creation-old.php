<?php
// require_once __DIR__ . '/vendor/autoload.php';

/**
 * 1. save_person_meta_boxes()
 * 2. Meat data save as a post in review 
 * 3. get_product_id_by_person()
 * 2. create_woocommerce_product_from_person()
 * 4. generate_pdf_from_person()
 * 5. send_product_creation_email()
 */

// Custom post saving functionality and create a product with pdf data 
function custom_save_post_person($post_id, $post, $update)
{
    // error_log(print_r($update, true));

    if ($post->post_status == 'publish') { // Check if the post type is 'person' and it's a new post
        // Save the review meta data
        save_person_meta_boxes($post_id);

        if ($update) {
            error_log(print_r('new post create', true));
            // Handle new post creation
            create_woocommerce_product_from_person($_POST, $post_id, $post);
        } else {
            // Optionally handle post update (if needed)
            //   update_woocommerce_product_from_person($post_id, $post);
            error_log(print_r('update', true));
        }
    }
}
add_action('save_post_person', 'custom_save_post_person', 20, 3); // Use higher priority to ensure the post is fully saved

// Create a product when create a new user 
function create_woocommerce_product_from_person($post_data, $person_id, $post)
{
    // Check if a WooCommerce product already exists for this person
    $existing_product_id = get_product_id_by_person($person_id);

    if ($existing_product_id) {
        return; // Product already exists, do nothing
    }

    $person_title = $post->post_title;

    error_log(print_r('gggggggggggggg', true));
    error_log(print_r($post_data, true));

    // Create new product
    $product = new WC_Product();
    $product->set_name($person_title);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_description(get_post_field('post_content', $person_id));
    $product->set_price(10); // Set price if needed
    $product->set_regular_price(10); // Set regular price if needed
    $product->set_downloadable(true);

    // Add meta data to link the product to the person
    $product->update_meta_data('_linked_person_id', $person_id);

    error_log(print_r('post_data extra----', true));
    error_log(print_r($post_data, true));

    // Uncomment the following lines to attach the PDF if you have implemented the generate_pdf_from_person function
    $pdf_url = generate_pdf_from_person($person_id, $post);
    $download = new WC_Product_Download();
    $download->set_name('Person PDF');
    $download->set_file($pdf_url);
    $product->set_downloads(array($download));
    $product_id = $product->save();

    // Send email notification
    send_product_creation_email($person_id, $pdf_url);
}

// Function to get product ID by person ID
function get_product_id_by_person($person_id)
{
    $args = array(
        'post_type' => 'product',
        'meta_key' => '_linked_person_id',
        'meta_value' => $person_id,
        'post_status' => 'publish',
        'fields' => 'ids',
    );

    $products = get_posts($args);
    return !empty($products) ? $products[0] : false;
}
