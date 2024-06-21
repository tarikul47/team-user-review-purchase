<?php

/**
 * Generate downloadable PDF and attach to WooCommerce product.
 *
 * @param int $person_id The person post ID.
 * @param WP_Post $post The post object.
 * @param array $post_data The $_POST data.
 * @param WC_Product|int $product The WooCommerce product object or ID.
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
            $content .= '<p>' . esc_html(strip_tags($post->post_content)) . '</p>'; // Strip HTML tags

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
        $pdf_file = $upload_dir['path'] . '/person_' . $person_id . '.pdf';
        $mpdf->Output($pdf_file, 'F');

        // Ensure the file has correct permissions
        chmod($pdf_file, 0644);

        // Check if the PDF file is correctly created
        if (!file_exists($pdf_file) || filesize($pdf_file) == 0) {
            throw new Exception('PDF file creation failed or the file is empty.');
        }

        // Add PDF as a downloadable file to the WooCommerce product
        $pdf_url = $upload_dir['url'] . '/person_' . $person_id . '.pdf';
        if (is_numeric($product)) {
            $product = wc_get_product($product);
        }

        if ($product instanceof WC_Product) {
            // Generate a unique ID for the downloadable file
            $download_id = wp_generate_uuid4();

            $downloads = array(
                $download_id => array(
                    'id' => $download_id,
                    'name' => 'Person PDF',
                    'file' => $pdf_url,
                    'enabled' => true
                )
            );

            // Update the downloadable files meta
            $product->set_downloads($downloads);
            $product->save();

            return $pdf_url;
        }

        return false; // Return false if product is invalid or PDF attachment fails
    } catch (Exception $e) {
        error_log('Error generating PDF: ' . $e->getMessage());
        return false;
    }
}
