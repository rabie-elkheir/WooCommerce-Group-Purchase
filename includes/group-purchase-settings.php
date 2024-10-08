<?php

// إذا تم استدعاء هذا الملف مباشرة، امنع الوصول
if (! defined('WPINC')) {
    die;
}

/**
 * إضافة إعدادات شراء المجموعة إلى صفحة المنتج في WooCommerce
 */
add_action('woocommerce_product_options_general_product_data', 'woocommerce_group_purchase_options');
function woocommerce_group_purchase_options()
{
    // إضافة خانة تحديد لتمكين شراء المجموعة
    woocommerce_wp_checkbox(array(
        'id' => 'enable_group_purchase',
        'label' => __('تمكين شراء المجموعة', 'woocommerce-group-purchase'),
        'description' => __('السماح للعملاء بشراء هذا المنتج كمجموعة.', 'woocommerce-group-purchase')
    ));

    // إضافة حقل إدخال لتحديد حجم المجموعة
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

    // إضافة حقل إدخال للخصم (مبلغ ثابت)
    woocommerce_wp_text_input(array(
        'id' => 'group_purchase_discount',
        'label' => __('خصم المجموعة (مبلغ)', 'woocommerce-group-purchase'),
        'description' => __('مبلغ الخصم الثابت على شراء المجموعة.', 'woocommerce-group-purchase'),
        'type' => 'number',
        'custom_attributes' => array(
            'min' => '0',
            'step' => '0.01'
        )
    ));

    // إضافة حقل إدخال لتحديد وقت الشراء (اختياري)
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

/**
 * حفظ الحقول عند حفظ المنتج
 */
add_action('woocommerce_process_product_meta', 'save_group_purchase_fields');
function save_group_purchase_fields($post_id)
{
    // حفظ خيار تمكين شراء المجموعة
    $enable_group_purchase = isset($_POST['enable_group_purchase']) ? 'yes' : 'no';
    update_post_meta($post_id, 'enable_group_purchase', $enable_group_purchase);

    // حفظ حجم المجموعة
    if (isset($_POST['group_purchase_size'])) {
        update_post_meta($post_id, 'group_purchase_size', sanitize_text_field($_POST['group_purchase_size']));
    }

    // حفظ خصم المجموعة (مبلغ ثابت)
    if (isset($_POST['group_purchase_discount'])) {
        update_post_meta($post_id, 'group_purchase_discount', sanitize_text_field($_POST['group_purchase_discount']));
    }

    // حفظ مدة شراء المجموعة
    if (isset($_POST['group_purchase_time_limit'])) {
        update_post_meta($post_id, 'group_purchase_time_limit', sanitize_text_field($_POST['group_purchase_time_limit']));
    }
}

/**
 * عرض سعر الخصم ومعلومات المجموعة في صفحة المنتج
 */
add_action('woocommerce_single_product_summary', 'display_group_purchase_info', 25);
function display_group_purchase_info()
{
    global $product;

    // التحقق من تمكين شراء المجموعة
    $enable_group_purchase = get_post_meta($product->get_id(), 'enable_group_purchase', true);
    if ($enable_group_purchase === 'yes') {
        $group_size = get_post_meta($product->get_id(), 'group_purchase_size', true);
        $group_discount = get_post_meta($product->get_id(), 'group_purchase_discount', true);

        if ($group_discount && $group_discount > 0) {
            // حساب السعر بعد الخصم
            $original_price = $product->get_price();
            $discounted_price = $original_price - $group_discount;

            // التأكد من أن السعر المخفض لا يصبح سالباً
            if ($discounted_price < 0) {
                $discounted_price = 0;
            }

            // عرض السعر الجديد بعد الخصم
            echo '<p>' . __('سعر المجموعة: ') . wc_price($discounted_price) . '</p>';
        }

        // عرض معلومات المجموعة
        echo '<p>' . __('هذا المنتج متاح للشراء كمجموعة.') . '</p>';
        echo '<p>' . __('عدد الأشخاص المطلوبين لإكمال المجموعة: ') . $group_size . '</p>';

        // هنا يمكنك إضافة كود لعرض عدد الأشخاص المتبقيين
        $current_buyers = 0; // هذا يمكن تحديثه بناءً على حالة الطلبات
        $remaining_people = $group_size - $current_buyers;

        if ($remaining_people > 0) {
            echo '<p>' . __('عدد الأشخاص المتبقين لإكمال المجموعة: ') . $remaining_people . '</p>';
        } else {
            echo '<p>' . __('المجموعة مكتملة! يمكنك الآن شراء المنتج بالسعر المخفض.') . '</p>';
        }
    }
}

/**
 * تطبيق خصم المجموعة في سلة التسوق
 */
add_action('woocommerce_before_calculate_totals', 'apply_group_purchase_discount');
function apply_group_purchase_discount($cart)
{
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    // المرور عبر المنتجات في السلة
    foreach ($cart->get_cart() as $cart_item) {
        $product_id = $cart_item['product_id'];

        // التحقق من تمكين شراء المجموعة
        $enable_group_purchase = get_post_meta($product_id, 'enable_group_purchase', true);
        if ($enable_group_purchase === 'yes') {
            $group_discount = get_post_meta($product_id, 'group_purchase_discount', true);

            // حساب الخصم وتطبيقه
            if ($group_discount && $group_discount > 0) {
                $original_price = $cart_item['data']->get_price();
                $new_price = $original_price - $group_discount;

                // التأكد من أن السعر الجديد لا يصبح سالباً
                if ($new_price < 0) {
                    $new_price = 0;
                }

                // تعيين السعر الجديد
                $cart_item['data']->set_price($new_price);
            }
        }
    }
}
