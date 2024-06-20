<?php

/**
 * Get WooCommerce product ID linked to a person ID
 *
 * This function retrieves the WooCommerce product ID that is associated
 * with a specific person ID. It queries the 'product' post type and looks
 * for a meta key '_linked_person_id' that matches the given person ID.
 *
 * @param int $person_id The ID of the person post to check.
 * @return int|false The product ID if found, or false if no product is linked.
 */
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
