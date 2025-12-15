<?php
require_once('wp-load.php');

$form_content = '
<div class="row">
    <div class="col-sm-6">
        <div class="mb-3">
            [text* your-name class:form-control placeholder "Име"]
        </div>
    </div>
    <div class="col-sm-6">
        <div class="mb-3">
            [email* your-email class:form-control placeholder "Имейл адрес"]
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="mb-3">
            [text* your-subject class:form-control placeholder "Тема"]
        </div>
    </div>
    <div class="col-sm-6">
        <div class="mb-3">
            [text your-phone class:form-control placeholder "Телефон"]
        </div>
    </div>
</div>

<div class="mb-3">
    [textarea* your-message class:form-control x7 placeholder "Вашето съобщение..."]
</div>

<div class="mb-5">
    <button type="submit" class="theme-btn btn-style-one"><span class="btn-title">Изпрати</span></button>
    <button type="reset" class="theme-btn btn-style-one bg-theme-color5"><span class="btn-title">Изчисти</span></button>
</div>
';

$post_data = array(
    'post_title'    => 'Contact Form Main',
    'post_content'  => $form_content,
    'post_status'   => 'publish',
    'post_type'     => 'wpcf7_contact_form'
);

// Check if form already exists
$existing_form = get_page_by_title('Contact Form Main', OBJECT, 'wpcf7_contact_form');

if ($existing_form) {
    $post_data['ID'] = $existing_form->ID;
    wp_update_post($post_data);
    $form_id = $existing_form->ID;
    echo "Updated Contact Form 7 ID: $form_id\n";
} else {
    $form_id = wp_insert_post($post_data);
    echo "Created Contact Form 7 ID: $form_id\n";
}

// Set default mail template (optional but good for functionality)
$mail = array(
    'subject' => 'Pino Villa Contact: [your-subject]',
    'sender' => '[your-name] <[your-email]>',
    'body' => "From: [your-name] <[your-email]>\nSubject: [your-subject]\nPhone: [your-phone]\n\nMessage Body:\n[your-message]",
    'recipient' => 'info@pinovilla.com',
    'additional_headers' => 'Reply-To: [your-email]',
    'attachments' => '',
    'use_html' => false,
    'exclude_blank' => false
);

update_post_meta($form_id, '_mail', $mail);

?>
