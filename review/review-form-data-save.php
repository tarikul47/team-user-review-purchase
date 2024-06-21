<?php

// Hook to initialize the custom endpoint
add_action('init', 'custom_handle_review_submission');

function custom_handle_review_submission()
{
    if (isset($_POST['submit_review'])) {

        // Sanitize form data
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $review_content = sanitize_textarea_field($_POST['review']);
        $rating = intval($_POST['rating']);
        $person_id = intval($_POST['person_id']);
        $author_id = intval($_POST['author_id']);

        // Check if a review already exists from this user for this person
        $existing_reviews = new WP_Query(
            array(
                'post_type' => 'review',
                'post_status' => 'any',
                'author' => $author_id,
                'meta_query' => array(
                    array(
                        'key' => '_person_profile_id',
                        'value' => $person_id,
                        'compare' => '='
                    )
                )
            )
        );

        if ($existing_reviews->have_posts()) {
            // Redirect with an error message if a review already exists
            wp_redirect(add_query_arg('review_error', 'already_exists', get_permalink($person_id)));
            exit;
        }

        // Create a new review post
        $review_id = wp_insert_post(
            array(
                'post_type' => 'review',
                'post_title' => 'Review by ' . $name,
                'post_content' => $review_content,
                'post_status' => 'pending',
                'post_author' => $author_id,
                'meta_input' => array(
                    '_person_profile_id' => $person_id,
                    '_reviewer_name' => $name,
                    '_reviewer_email' => $email,
                    '_review_rating' => $rating,
                )
            )
        );

        if ($review_id) {
            // Redirect to avoid resubmission on refresh
            wp_redirect(add_query_arg('review_submitted', 'true', get_permalink($person_id)));
            exit;
        } else {
            // Handle error
            wp_die('There was an error submitting your review. Please try again.');
        }
    }
}
