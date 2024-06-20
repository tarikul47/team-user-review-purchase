<?php

function my_save_post_person($post_id, $post, $update)
{
    // error_log('save_post_person হুক চালু হয়েছে');

    // check_exist_product
    get_product_id_by_person($post_id, $_POST);

    // create woocommerce product creation 
    create_woocommerce_product_from_person($post_id, $_POST);

    // Woocommerce product downloadable pdf creation 
    generate_pdf_from_person($post_id, $_POST);

    // Meta data save 
    save_metabox_data($post_id, $_POST);

}
add_action('save_post_person', 'my_save_post_person', 10, 3);


function get_product_id_by_person($post_id, $post_data)
{
    error_log(print_r($post_data, true));
}
function create_woocommerce_product_from_person($post_id, $post_data)
{
    error_log(print_r($post_data, true));
}
function generate_pdf_from_person($post_id, $post_data)
{
    error_log(print_r($post_data, true));
}
function save_metabox_data($post_id, $post_data)
{
    error_log(print_r($post_data, true));
}


















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
    var_dump($review_rating);
    var_dump($review_rating == '4');
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