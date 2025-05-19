<?php
// Đăng ký meta box cho post views
function register_post_views_meta() {
    register_post_meta('post', 'post_views_count', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'integer',
        'default' => 0,
        'sanitize_callback' => 'absint',
        'auth_callback' => function() { 
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'register_post_views_meta');

// Hàm lấy số lượt xem
function get_post_views($post_id) {
    $count = get_post_meta($post_id, 'post_views_count', true);
    return empty($count) ? 0 : $count;
}

// Hàm cập nhật lượt xem
function set_post_views($post_id) {
    if (!is_single()) return;
    
    if (empty($_COOKIE['post_viewed_' . $post_id])) {
        $count = get_post_views($post_id);
        $count++;
        update_post_meta($post_id, 'post_views_count', $count);
        setcookie('post_viewed_' . $post_id, '1', time() + 86400, '/'); // Cookie hết hạn sau 24h
    }
}

// Hook để đếm lượt xem khi xem bài viết
function count_post_views() {
    if (is_single()) {
        set_post_views(get_the_ID());
    }
}
add_action('wp', 'count_post_views');

// Thêm cột Post Views trong admin
function add_post_views_column($columns) {
    $columns['post_views'] = 'Lượt xem';
    return $columns;
}
add_filter('manage_posts_columns', 'add_post_views_column');

// Hiển thị số lượt xem trong cột admin
function show_post_views_column($column, $post_id) {
    if ($column === 'post_views') {
        echo number_format(get_post_views($post_id));
    }
}
add_action('manage_posts_custom_column', 'show_post_views_column', 10, 2);

// Format số lượt xem
function format_post_views($views) {
    if ($views >= 1000000) {
        return round($views / 1000000, 1) . 'M';
    } elseif ($views >= 1000) {
        return round($views / 1000, 1) . 'K';
    }
    return $views;
} 