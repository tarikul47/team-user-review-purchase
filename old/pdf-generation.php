<?php
require 'vendor/autoload.php';

function generate_pdf_from_person($person_id, $post)
{
    // Retrieve meta data from the database
    $review_title = get_post_meta($person_id, '_review_title', true);
    $review_content = get_post_meta($person_id, '_review_content', true);
    $review_rating = get_post_meta($person_id, '_review_rating', true);

    // Log the data for debugging
    error_log(print_r($review_title, true));
    error_log(print_r($review_content, true));
    error_log(print_r($review_rating, true));

    // Prepare content for PDF
    $content = '';

    if ($person_id) {
        $content .= '<h1>' . esc_html($post->post_title) . '</h1>';
        $content .= '<p>' . esc_html($post->post_content) . '</p>';

        if ($review_title) {
            $content .= '<h2>Review Title: ' . esc_html($review_title) . '</h2>';
        }
        if ($review_content) {
            $content .= '<p>Review Content: ' . esc_html($review_content) . '</p>';
        }
        if ($review_rating) {
            $content .= '<p>Review Rating: ' . esc_html($review_rating) . '</p>';
        }
    }

    // Generate PDF
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($content);

    $upload_dir = wp_upload_dir();
    $pdf_path = $upload_dir['basedir'] . '/pdfs/';
    if (!file_exists($pdf_path)) {
        mkdir($pdf_path, 0755, true);
    }

    $pdf_file = $pdf_path . 'person_' . $person_id . '.pdf';
    $mpdf->Output($pdf_file, 'F');

    return $upload_dir['baseurl'] . '/pdfs/person_' . $person_id . '.pdf';
}
