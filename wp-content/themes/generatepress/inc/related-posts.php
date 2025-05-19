<?php
/**
 * Related posts functionality
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Hiển thị bài viết liên quan
function display_related_posts() {
    if (is_single()) {
        global $post;
        
        // Lấy chuyên mục của bài viết hiện tại
        $categories = get_the_category($post->ID);
        if ($categories) {
            $category_ids = array();
            foreach ($categories as $category) {
                $category_ids[] = $category->term_id;
            }
            
            // Query bài viết liên quan
            $related_args = array(
                'post_type' => 'post',
                'posts_per_page' => 8,
                'post__not_in' => array($post->ID),
                'category__in' => $category_ids,
                'orderby' => 'date',
                'order' => 'DESC'
            );
            
            $related_query = new WP_Query($related_args);
            
            if ($related_query->have_posts()) {
                echo '<div class="related-posts-section">';
                echo '<h3 class="related-posts-title">Bài viết cùng chuyên mục</h3>';
                echo '<div class="related-posts-grid">';
                
                while ($related_query->have_posts()) {
                    $related_query->the_post();
                    ?>
                    <article class="related-post">
                        <a href="<?php the_permalink(); ?>" class="related-post-link">
                            <div class="related-post-thumbnail">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/default-thumbnail.jpg" alt="<?php the_title_attribute(); ?>">
                                <?php endif; ?>
                                
                                <?php
                                // Hiển thị chuyên mục
                                $categories = get_the_category();
                                if ($categories) {
                                    $category = $categories[0];
                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link">' . esc_html($category->name) . '</a>';
                                }
                                ?>
                                
                                <div class="related-post-meta">
                                    <span class="post-time">
                                        <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' trước'; ?>
                                    </span>
                                    <span class="post-views">
                                        <i class="fas fa-eye"></i>
                                        <?php echo get_post_views(get_the_ID()); ?>
                                    </span>
                                </div>
                            </div>
                            <h4 class="related-post-title"><?php the_title(); ?></h4>
                        </a>
                    </article>
                    <?php
                }
                
                echo '</div>'; // .related-posts-grid
                
                // Nút tải thêm
                if ($related_query->max_num_pages > 1) {
                    echo '<div class="load-more-container">';
                    echo '<button class="load-more-related" data-page="1" data-category="' . implode(',', $category_ids) . '" data-post-id="' . $post->ID . '">';
                    echo '<i class="fas fa-sync-alt"></i> Tải thêm bài viết';
                    echo '</button>';
                    echo '</div>';
                }
                
                echo '</div>'; // .related-posts-section
                
                wp_reset_postdata();
            }
        }
    }
}
add_action('generate_after_entry_content', 'display_related_posts');

// AJAX handler cho việc tải thêm bài viết liên quan
function load_more_related_posts() {
    check_ajax_referer('load_more_related_nonce', 'nonce');
    
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $category_ids = isset($_POST['category']) ? explode(',', $_POST['category']) : array();
    $current_post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    if (empty($category_ids) || empty($current_post_id)) {
        wp_send_json_error('Invalid parameters');
        return;
    }
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 8,
        'paged' => $page,
        'post__not_in' => array($current_post_id),
        'category__in' => $category_ids,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <article class="related-post">
                <a href="<?php the_permalink(); ?>" class="related-post-link">
                    <div class="related-post-thumbnail">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium'); ?>
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/default-thumbnail.jpg" alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
                        
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            $category = $categories[0];
                            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link">' . esc_html($category->name) . '</a>';
                        }
                        ?>
                        
                        <div class="related-post-meta">
                            <span class="post-time">
                                <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' trước'; ?>
                            </span>
                            <span class="post-views">
                                <i class="fas fa-eye"></i>
                                <?php echo get_post_views(get_the_ID()); ?>
                            </span>
                        </div>
                    </div>
                    <h4 class="related-post-title"><?php the_title(); ?></h4>
                </a>
            </article>
            <?php
        }
        $html = ob_get_clean();
        wp_send_json_success(array(
            'html' => $html,
            'has_more' => $page < $query->max_num_pages
        ));
    } else {
        wp_send_json_error('No more posts');
    }
    
    wp_reset_postdata();
}
add_action('wp_ajax_load_more_related_posts', 'load_more_related_posts');
add_action('wp_ajax_nopriv_load_more_related_posts', 'load_more_related_posts');

// Thêm script cho việc tải thêm bài viết
function add_related_posts_scripts() {
    if (is_single()) {
        wp_enqueue_script('jquery');
        ?>
        <script>
        jQuery(document).ready(function($) {
            $('.load-more-related').on('click', function() {
                var button = $(this);
                var page = parseInt(button.data('page')) + 1;
                var category = button.data('category');
                var postId = button.data('post-id');
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'load_more_related_posts',
                        nonce: '<?php echo wp_create_nonce('load_more_related_nonce'); ?>',
                        page: page,
                        category: category,
                        post_id: postId
                    },
                    beforeSend: function() {
                        button.html('<i class="fas fa-spinner fa-spin"></i> Đang tải...');
                        button.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success && response.data.html) {
                            $('.related-posts-grid').append(response.data.html);
                            button.data('page', page);
                            button.html('<i class="fas fa-sync-alt"></i> Tải thêm bài viết');
                            button.prop('disabled', false);
                            
                            if (!response.data.has_more) {
                                button.remove();
                            }
                        } else {
                            button.remove();
                        }
                    },
                    error: function() {
                        button.html('<i class="fas fa-sync-alt"></i> Tải thêm bài viết');
                        button.prop('disabled', false);
                    }
                });
            });
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'add_related_posts_scripts'); 