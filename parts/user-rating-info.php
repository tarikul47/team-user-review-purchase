<?php

// বর্তমান ব্যক্তির আইডি পাওয়া
$person_id = get_the_ID();

// এই ব্যক্তির জন্য WooCommerce পণ্য তৈরি বা খুঁজে বের করা
$product_id = get_product_id_by_person($person_id);

// if (!$product_id) {
//     echo '<strong>Failed to create or retrieve product for this person.</strong>';
//     return;
// }

// Query for reviews by the current user
$reviews = new WP_Query(
    array(
        'post_type' => 'review',
        'post_status' => 'any',
        'posts_per_page' => -1, // Ensure all reviews are retrieved
        'meta_query' => array(
            array(
                'key' => '_person_profile_id',
                'value' => get_the_ID(),
                'compare' => '='
            )
        )
    )
);

//echo ;
//print_r($reviews);

$total_rating = 0;
$review_count = 0;

if ($reviews->have_posts()) {
    echo '<ul>';
    while ($reviews->have_posts()) {
        $reviews->the_post();
        $rating = get_post_meta(get_the_ID(), '_review_rating', true);
        $total_rating += $rating;
        $review_count++;
        //   echo '<li>';
        //  echo '<strong>Rating:</strong> ' . esc_html($rating) . '/5<br>';
        //  echo '</li>';
    }
    echo '</ul>';

    if ($review_count > 0) {
        $average_rating = $total_rating / $review_count;
        echo '<strong>Reviews Count:</strong> ' . esc_html(number_format($review_count, 2)) . '<br>';
        echo '<strong>Average Rating:</strong> ' . esc_html(number_format($average_rating, 2)) . '/5<br>';
        // Add to Cart button
        //   $product_id = 123; // Replace with your WooCommerce product ID
        echo '<a href="' . esc_url(wc_get_cart_url() . '?add-to-cart=' . $product_id) . '" class="button add_to_cart_button">Add Reviews to Cart</a>';
    } else {
        echo '<strong>No reviews found.</strong>';
    }
} else {
    echo '<strong>No reviews found.</strong>';
}

wp_reset_postdata();