<?php
/**
 * Create or update WooCommerce product from person
 * 
 * @param int $person_id The person post ID.
 * @param WP_Post $post The post object.
 * @param array $post_data The $_POST data.
 * @param int|false $existing_product_id The existing product ID, if any.
 * @return WC_Product|false The WooCommerce product object if created, or false if no new product was created.
 */
function create_or_update_woocommerce_product_from_person($person_id, $post, $post_data, $existing_product_id)
{
    if ($existing_product_id) {
        // Product already exists, return false
        return false;
    }

    $person_title = $post->post_title;

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

    $product->save();

    return $product;
}
