<?php
require 'vendor/autoload.php';

function generate_pdf_from_person($person_id, $post)
{
    // $person = get_post($person_id);
    $content = '';

    if ($person_id) {
        $content .= '<h1>' . $post->post_title . '</h1>';
        $content .= '<p>' . $post->post_content . '</p>';

        // Add custom fields here
        //  $custom_field_value = get_post_meta($person_id, 'custom_field_key', true);
        //  $content .= '<p>Custom Field: ' . $custom_field_value . '</p>';
    }

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