<?php

function save_metabox_data($person_id)
{
    // Check if the necessary data is available in $_POST
    if (isset($_POST['review_title'], $_POST['review_content'], $_POST['review_rating'])) {
        // Sanitize the input data
        $review_title = sanitize_text_field($_POST['review_title']);
        $review_content = sanitize_textarea_field($_POST['review_content']);
        $review_rating = sanitize_text_field($_POST['review_rating']);

        // Update the meta data for the person post
        update_post_meta($person_id, '_review_title', $review_title);
        update_post_meta($person_id, '_review_content', $review_content);
        update_post_meta($person_id, '_review_rating', $review_rating);
    }
}