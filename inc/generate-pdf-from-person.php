<?php
/**
 * Generate downloadable PDF
 *
 * @param int $person_id The person post ID.
 * @param WP_Post $post The post object.
 * @param array $post_data The $_POST data.
 * @return string|false The PDF URL if generated, or false on failure.
 */
function generate_pdf_from_person($person_id, $post, $post_data, $product)
{
    try {
        // Extract metadata from $_POST
        $review_title = isset($_POST['review_title']) ? sanitize_text_field($_POST['review_title']) : '';
        $review_content = isset($_POST['review_content']) ? sanitize_text_field($_POST['review_content']) : '';
        $review_rating = isset($_POST['review_rating']) ? sanitize_text_field($_POST['review_rating']) : '';

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

        // Save PDF to server
        $upload_dir = wp_upload_dir();
        $pdf_path = $upload_dir['basedir'] . '/pdfs/';
        if (!file_exists($pdf_path)) {
            mkdir($pdf_path, 0755, true);
        }

        $pdf_file = $pdf_path . 'person_' . $person_id . '.pdf';
        $mpdf->Output($pdf_file, 'F');

        // Add PDF as a downloadable file to the WooCommerce product
        if ($product instanceof WC_Product) {
            $download = new WC_Product_Download();
            $download->set_name('Person PDF');
            $download->set_file($upload_dir['baseurl'] . '/pdfs/person_' . $person_id . '.pdf');
            $product->set_downloads(array($download));
            $product->save();

            return $upload_dir['baseurl'] . '/pdfs/person_' . $person_id . '.pdf';
        } elseif (is_numeric($product)) {
            $product_obj = wc_get_product($product);
            if ($product_obj) {
                return generate_pdf_from_person($person_id, $post, $post_data, $product_obj); // Recursive call with product object
            }
        }

        return false; // Return false if product is invalid or PDF attachment fails
    } catch (Exception $e) {
        error_log('Error generating PDF: ' . $e->getMessage());
        return false;
    }
}