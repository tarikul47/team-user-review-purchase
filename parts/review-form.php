<?php

// current post id [ here post is member ]
$person_id = get_the_ID();
$current_user_id = get_current_user_id();

// Check if the current user has already submitted a review for this member
$existing_reviews = new WP_Query(
    array(
        'post_type' => 'review',
        'post_status' => 'any',
        'author' => $current_user_id,
        'meta_query' => array(
            array(
                'key' => '_person_profile_id',
                'value' => $person_id,
                'compare' => '='
            )
        )
    )
);
?>

<?php if (isset($_GET['review_submitted']) && $_GET['review_submitted'] == 'true'): ?>
    <p style="color: red;">Thank you for your review. It is pending approval.</p>
<?php elseif (isset($_GET['review_error']) && $_GET['review_error'] == 'already_exists'): ?>
    <p>You have already submitted a review for this profile.</p>
<?php elseif ($existing_reviews->have_posts()): ?>
    <p style="color: red;">You have already submitted a review for this profile.</p>
<?php else: ?>
    <div class="member-form">
        <h2>Submit Your Review</h2>
        <form action="<?php echo esc_url(get_permalink()); ?>" method="post">
            <p>
                <label for="name">Name</label><br>
                <input type="text" name="name" id="name" required>
            </p>
            <p>
                <label for="email">Email</label><br>
                <input type="email" name="email" id="email" required>
            </p>
            <p>
                <label for="review">Review</label><br>
                <textarea name="review" id="review" rows="5" required></textarea>
            </p>
            <p>
                <label for="rating">Rating</label><br>
                <select name="rating" id="rating" required>
                    <option value="">Select Rating</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </p>
            <p>
                <input type="hidden" name="person_id" value="<?php echo get_the_ID(); ?>">
                <input type="hidden" name="author_id" value="<?php echo get_current_user_id(); ?>">
                <input type="submit" name="submit_review" value="Submit Review">
            </p>
        </form>
    </div>
<?php endif;