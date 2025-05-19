<?php
/**
 * Search functionality
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Modify search query to include category and view count filters
function modify_search_query($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // Xử lý tìm kiếm theo chuyên mục
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $query->set('cat', intval($_GET['category']));
        }

        // Xử lý sắp xếp theo lượt xem
        if (isset($_GET['views']) && !empty($_GET['views'])) {
            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'post_views',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => 'post_views',
                    'value' => '0',
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                )
            );
            
            $query->set('meta_query', $meta_query);
            $query->set('meta_key', 'post_views');
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_type', 'NUMERIC');
            
            if ($_GET['views'] === 'high') {
                $query->set('order', 'DESC');
            } elseif ($_GET['views'] === 'low') {
                $query->set('order', 'ASC');
            }
        }

        // Xử lý tìm kiếm theo thời gian
        if (isset($_GET['date']) && !empty($_GET['date'])) {
            $date_query = array();
            
            switch ($_GET['date']) {
                case 'today':
                    $date_query = array(
                        'after' => '1 day ago',
                        'inclusive' => true
                    );
                    break;
                case 'week':
                    $date_query = array(
                        'after' => '1 week ago',
                        'inclusive' => true
                    );
                    break;
                case 'month':
                    $date_query = array(
                        'after' => '1 month ago',
                        'inclusive' => true
                    );
                    break;
                case 'year':
                    $date_query = array(
                        'after' => '1 year ago',
                        'inclusive' => true
                    );
                    break;
            }
            
            if (!empty($date_query)) {
                $query->set('date_query', array($date_query));
            }
        }

        // Chỉ tìm kiếm trong post type 'post'
        $query->set('post_type', 'post');
        
        // Số bài viết trên mỗi trang
        $query->set('posts_per_page', 12);

        // Thêm sắp xếp mặc định theo ngày đăng nếu không có bộ lọc nào được chọn
        if (!isset($_GET['views']) || empty($_GET['views'])) {
            $query->set('orderby', 'date');
            $query->set('order', 'DESC');
        }
    }
    return $query;
}
add_action('pre_get_posts', 'modify_search_query');

// Hiển thị kết quả tìm kiếm
function display_search_results() {
    if (is_search()) {
        global $wp_query;
        $total_results = $wp_query->found_posts;
        $search_query = get_search_query();
        
        echo '<div class="search-results-header">';
        if ($total_results > 0) {
            echo '<h2>Tìm thấy ' . number_format($total_results) . ' kết quả cho "' . esc_html($search_query) . '"</h2>';
            
            // Hiển thị các bộ lọc đang áp dụng
            $active_filters = array();
            
            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $category = get_category(intval($_GET['category']));
                if ($category) {
                    $active_filters[] = 'Chuyên mục: ' . $category->name;
                }
            }
            
            if (isset($_GET['views']) && !empty($_GET['views'])) {
                $views_text = $_GET['views'] === 'high' ? 'Lượt xem: Cao đến thấp' : 'Lượt xem: Thấp đến cao';
                $active_filters[] = $views_text;
            }

            if (isset($_GET['date']) && !empty($_GET['date'])) {
                $date_texts = array(
                    'today' => 'Hôm nay',
                    'week' => '7 ngày qua',
                    'month' => '30 ngày qua',
                    'year' => '365 ngày qua'
                );
                if (isset($date_texts[$_GET['date']])) {
                    $active_filters[] = 'Thời gian: ' . $date_texts[$_GET['date']];
                }
            }
            
            if (!empty($active_filters)) {
                echo '<div class="active-filters">';
                echo '<span class="filter-label">Bộ lọc đang dùng:</span>';
                foreach ($active_filters as $filter) {
                    echo '<span class="filter-tag">' . esc_html($filter) . '</span>';
                }
                echo '<a href="' . esc_url(home_url('/?s=' . urlencode($search_query))) . '" class="clear-filters">Xóa bộ lọc</a>';
                echo '</div>';
            }
        } else {
            echo '<div class="no-results">';
            echo '<h2>Không tìm thấy kết quả cho "' . esc_html($search_query) . '"</h2>';
            echo '<p>Thử tìm kiếm với từ khóa khác hoặc thay đổi bộ lọc.</p>';
            
            // Hiển thị từ khóa gợi ý
            $suggested_terms = get_terms(array(
                'taxonomy' => 'post_tag',
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 5,
                'hide_empty' => true
            ));
            
            if (!empty($suggested_terms) && !is_wp_error($suggested_terms)) {
                echo '<div class="suggested-terms">';
                echo '<p>Các từ khóa phổ biến:</p>';
                foreach ($suggested_terms as $term) {
                    echo '<a href="' . esc_url(home_url('/?s=' . urlencode($term->name))) . '" class="suggested-term">';
                    echo esc_html($term->name);
                    echo '</a>';
                }
                echo '</div>';
            }
            echo '</div>';
        }
        echo '</div>';
    }
}
add_action('generate_before_main_content', 'display_search_results');

// Thêm CSS cho trang kết quả tìm kiếm
function add_search_results_styles() {
    if (is_search()) {
        ?>
        <style>
            .search-results-header {
                background: #1b1b1b;
                padding: 25px;
                margin-bottom: 30px;
                border-radius: 10px;
                color: #fff;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .search-results-header h2 {
                margin: 0;
                font-size: 1.5em;
                color: #fff;
                font-weight: 600;
            }

            .active-filters {
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid rgba(255,255,255,0.1);
            }

            .filter-label {
                display: inline-block;
                margin-right: 10px;
                color: #888;
                font-size: 0.9em;
            }

            .filter-tag {
                display: inline-block;
                padding: 5px 12px;
                margin: 5px;
                background: rgba(255,0,0,0.1);
                border: 1px solid rgba(255,0,0,0.2);
                border-radius: 15px;
                color: #ff0000;
                font-size: 0.85em;
            }

            .clear-filters {
                display: inline-block;
                padding: 5px 15px;
                margin-left: 10px;
                background: #ff0000;
                color: #fff;
                text-decoration: none;
                border-radius: 15px;
                font-size: 0.85em;
                transition: all 0.3s ease;
            }

            .clear-filters:hover {
                background: #cc0000;
                transform: translateY(-1px);
            }

            .no-results {
                text-align: center;
                padding: 50px 25px;
                background: #1b1b1b;
                border-radius: 10px;
                color: #fff;
            }

            .no-results h2 {
                margin: 0 0 15px 0;
                color: #fff;
                font-size: 1.8em;
            }

            .no-results p {
                margin: 0 0 20px 0;
                color: #888;
            }

            .suggested-terms {
                margin-top: 25px;
            }

            .suggested-terms p {
                margin-bottom: 10px;
                color: #888;
            }

            .suggested-term {
                display: inline-block;
                padding: 8px 15px;
                margin: 5px;
                background: #2d2d2d;
                color: #fff;
                text-decoration: none;
                border-radius: 20px;
                transition: all 0.3s ease;
            }

            .suggested-term:hover {
                background: #ff0000;
                transform: translateY(-1px);
            }

            @media (max-width: 768px) {
                .search-results-header {
                    padding: 20px;
                }

                .search-results-header h2 {
                    font-size: 1.2em;
                }

                .filter-tag {
                    font-size: 0.8em;
                }

                .clear-filters {
                    display: block;
                    text-align: center;
                    margin: 10px 0 0 0;
                }
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'add_search_results_styles'); 