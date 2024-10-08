<?php
// group-purchase-settings.php

// إضافة إعدادات شراء المجموعة إلى صفحة المنتج في WooCommerce
add_action('woocommerce_product_options_general_product_data', 'woocommerce_group_purchase_options');
function woocommerce_group_purchase_options()
{
    woocommerce_wp_checkbox(array(
        'id' => 'enable_group_purchase',
        'label' => __('تمكين شراء المجموعة', 'woocommerce-group-purchase'),
        'description' => __('السماح للعملاء بشراء هذا المنتج كمجموعة.', 'woocommerce-group-purchase')
    ));

    woocommerce_wp_text_input(array(
        'id' => 'group_purchase_size',
        'label' => __('حجم المجموعة', 'woocommerce-group-purchase'),
        'description' => __('عدد الأشخاص المطلوبين لتفعيل شراء المجموعة.', 'woocommerce-group-purchase'),
        'type' => 'number',
        'custom_attributes' => array(
            'min' => '1',
            'step' => '1'
        )
    ));

    woocommerce_wp_text_input(array(
        'id' => 'group_purchase_discount',
        'label' => __('خصم المجموعة (%)', 'woocommerce-group-purchase'),
        'description' => __('النسبة المئوية للخصم على شراء المجموعة.', 'woocommerce-group-purchase'),
        'type' => 'number',
        'custom_attributes' => array(
            'min' => '0',
            'step' => '0.1'
        )
    ));

    woocommerce_wp_text_input(array(
        'id' => 'group_purchase_time_limit',
        'label' => __('مدة شراء المجموعة (ساعات)', 'woocommerce-group-purchase'),
        'description' => __('عدد الساعات التي يكون الشراء متاحًا خلالها (اختياري).', 'woocommerce-group-purchase'),
        'type' => 'number',
        'custom_attributes' => array(
            'min' => '0',
            'step' => '1'
        )
    ));
}

// حفظ الحقول
add_action('woocommerce_process_product_meta', 'save_group_purchase_fields');
function save_group_purchase_fields($post_id)
{
    $enable_group_purchase = isset($_POST['enable_group_purchase']) ? 'yes' : 'no';
    update_post_meta($post_id, 'enable_group_purchase', $enable_group_purchase);

    if (isset($_POST['group_purchase_size'])) {
        update_post_meta($post_id, 'group_purchase_size', sanitize_text_field($_POST['group_purchase_size']));
    }

    if (isset($_POST['group_purchase_discount'])) {
        update_post_meta($post_id, 'group_purchase_discount', sanitize_text_field($_POST['group_purchase_discount']));
    }

    if (isset($_POST['group_purchase_time_limit'])) {
        update_post_meta($post_id, 'group_purchase_time_limit', sanitize_text_field($_POST['group_purchase_time_limit']));
    }
}
