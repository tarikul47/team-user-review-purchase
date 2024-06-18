<?php
// require_once __DIR__ . '/vendor/autoload.php';

// Custom post saving functionality and create a product with pdf data 
function custom_save_post_person($post_id, $post, $update)
{
    if ($post->post_status == 'publish') { // Check if the post type is 'person' and it's a new post
       create_woocommerce_product_from_person($post_id, $post);
    }
}
add_action('save_post_person', 'custom_save_post_person', 20, 3); // Use higher priority to ensure the post is fully saved

// Create a product when create a new user 
function create_woocommerce_product_from_person($person_id, $post)
{
    // Check if a WooCommerce product already exists for this person
    $existing_product_id = get_product_id_by_person($person_id);

    if ($existing_product_id) {
        return; // Product already exists, do nothing
    }

    $person_title = $post->post_title;

    error_log(print_r($person_title, true));

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

    // Save the product
    $product_id = $product->save();

    // Uncomment the following lines to attach the PDF if you have implemented the generate_pdf_from_person function
    // $pdf_url = generate_pdf_from_person($person_id);
    // $download = new WC_Product_Download();
    // $download->set_name('Person PDF');
    // $download->set_file($pdf_url);
    // $product->set_downloads(array($download));
    // $product->save();
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

