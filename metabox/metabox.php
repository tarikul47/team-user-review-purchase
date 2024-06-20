<?php
function add_person_meta_boxes()
{
    add_meta_box(
        'person_review_meta_box',
        'Review Details',
        'display_person_review_meta_box',
        'person',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_person_meta_boxes');

function display_person_review_meta_box($post)
{
    $review_title = get_post_meta($post->ID, '_review_title', true);
    $review_content = get_post_meta($post->ID, '_review_content', true);
    $review_rating = get_post_meta($post->ID, '_review_rating', true);
   // var_dump($review_rating);
    //var_dump($review_rating == '4');
    ?>
    <p>
        <label for="review_title">Review Title</label>
        <input type="text" name="review_title" id="review_title" value="<?php echo esc_attr($review_title); ?>" />
    </p>
    <p>
        <label for="review_content">Review Content</label>
        <textarea name="review_content" id="review_content"><?php echo esc_textarea($review_content); ?></textarea>
    </p>
    <p>
        <label for="rating">Rating</label><br>
        <select name="review_rating" id="rating" required>
            <option value="">Select Rating</option>
            <option <?php echo $review_rating == '1' ? 'selected' : ''; ?> value="1">1</option>
            <option <?php echo $review_rating == '2' ? 'selected' : ''; ?> value="2">2</option>
            <option <?php echo $review_rating == '3' ? 'selected' : ''; ?> value="3">3</option>
            <option <?php echo $review_rating == '4' ? 'selected' : ''; ?> value="4">4</option>
            <option <?php echo $review_rating == '5' ? 'selected' : ''; ?> value="5">5</option>
        </select>
    </p>
    <?php
}

// function save_person_meta_boxes($person_id)
// {
//     // Check if the necessary data is available in $_POST
//     if (isset($_POST['review_title'], $_POST['review_content'], $_POST['review_rating'])) {
//         // Sanitize the input data
//         $review_title = sanitize_text_field($_POST['review_title']);
//         $review_content = sanitize_textarea_field($_POST['review_content']);
//         $review_rating = sanitize_text_field($_POST['review_rating']);

//         // Update the meta data for the person post
//         update_post_meta($person_id, '_review_title', $review_title);
//         update_post_meta($person_id, '_review_content', $review_content);
//         update_post_meta($person_id, '_review_rating', $review_rating);

//         // Check if a review already exists for this person by the current user
//         $current_user_id = get_current_user_id();
//         $existing_reviews = get_posts(
//             array(
//                 'post_type' => 'review',
//                 'meta_query' => array(
//                     array(
//                         'key' => '_reviewed_person_id',
//                         'value' => $person_id,
//                         'compare' => '='
//                     ),
//                     array(
//                         'key' => '_review_author_id',
//                         'value' => $current_user_id,
//                         'compare' => '='
//                     )
//                 ),
//                 'post_status' => 'publish',
//                 'posts_per_page' => 1
//             )
//         );

//         if ($existing_reviews) {
//             // If a review exists, get the existing review data
//             $existing_review_id = $existing_reviews[0]->ID;
//             $existing_review = get_post($existing_review_id);
//             $existing_review_title = $existing_review->post_title;
//             $existing_review_content = $existing_review->post_content;
//             $existing_review_rating = get_post_meta($existing_review_id, '_review_rating', true);

//             // Check if there are any changes in the review data
//             if ($review_title !== $existing_review_title || $review_content !== $existing_review_content || $review_rating !== $existing_review_rating) {
//                 // Update the existing review if there are changes
//                 $review_post = array(
//                     'ID' => $existing_review_id,
//                     'post_title' => $review_title,
//                     'post_content' => $review_content,
//                 );
//                 wp_update_post($review_post);

//                 // Update meta data for the existing review
//                 update_post_meta($existing_review_id, '_review_rating', $review_rating);
//             }
//         } else {
//             // Create a new review post if none exists
//             $review_post = array(
//                 'post_title' => $review_title,
//                 'post_content' => $review_content,
//                 'post_status' => 'publish',
//                 'post_type' => 'review',
//                 'post_author' => $current_user_id,
//                 'meta_input' => array(
//                     '_reviewed_person_id' => $person_id,
//                     '_review_author_id' => $current_user_id,
//                     '_review_rating' => $review_rating
//                 )
//             );
//             wp_insert_post($review_post);
//         }
//     }
// }



