<?php
/**
 * GeneratePress.
 *
 * Please do not make any edits to this file. All edits should be done in a child theme.
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Set our theme version.
define( 'GENERATE_VERSION', '3.6.0' );

if ( ! function_exists( 'generate_setup' ) ) {
	add_action( 'after_setup_theme', 'generate_setup' );
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since 0.1
	 */
	function generate_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'generatepress' );

		// Add theme support for various features.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'status' ) );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );

		$color_palette = generate_get_editor_color_palette();

		if ( ! empty( $color_palette ) ) {
			add_theme_support( 'editor-color-palette', $color_palette );
		}

		add_theme_support(
			'custom-logo',
			array(
				'height' => 70,
				'width' => 350,
				'flex-height' => true,
				'flex-width' => true,
			)
		);

		// Register primary menu.
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'generatepress' ),
			)
		);

		/**
		 * Set the content width to something large
		 * We set a more accurate width in generate_smart_content_width()
		 */
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 1200; /* pixels */
		}

		// Add editor styles to the block editor.
		add_theme_support( 'editor-styles' );

		$editor_styles = apply_filters(
			'generate_editor_styles',
			array(
				'assets/css/admin/block-editor.css',
			)
		);

		add_editor_style( $editor_styles );
	}
}

/**
 * Get all necessary theme files
 */
$theme_dir = get_template_directory();

require $theme_dir . '/inc/theme-functions.php';
require $theme_dir . '/inc/defaults.php';
require $theme_dir . '/inc/class-css.php';
require $theme_dir . '/inc/css-output.php';
require $theme_dir . '/inc/general.php';
require $theme_dir . '/inc/customizer.php';
require $theme_dir . '/inc/markup.php';
require $theme_dir . '/inc/typography.php';
require $theme_dir . '/inc/plugin-compat.php';
require $theme_dir . '/inc/block-editor.php';
require $theme_dir . '/inc/class-typography.php';
require $theme_dir . '/inc/class-typography-migration.php';
require $theme_dir . '/inc/class-html-attributes.php';
require $theme_dir . '/inc/class-theme-update.php';
require $theme_dir . '/inc/class-rest.php';
require $theme_dir . '/inc/deprecated.php';

if ( is_admin() ) {
	require $theme_dir . '/inc/meta-box.php';
	require $theme_dir . '/inc/class-dashboard.php';
}

/**
 * Load our theme structure
 */
require $theme_dir . '/inc/structure/archives.php';
require $theme_dir . '/inc/structure/comments.php';
require $theme_dir . '/inc/structure/featured-images.php';
require $theme_dir . '/inc/structure/footer.php';
require $theme_dir . '/inc/structure/header.php';
require $theme_dir . '/inc/structure/navigation.php';
require $theme_dir . '/inc/structure/post-meta.php';
require $theme_dir . '/inc/structure/sidebars.php';
require $theme_dir . '/inc/structure/search-modal.php';

// Include post views functionality
require get_template_directory() . '/inc/post-views.php';

// Include related posts functionality
require get_template_directory() . '/inc/related-posts.php';

// Include search functionality
require get_template_directory() . '/inc/search-functions.php';

// Add custom CSS to header
function add_custom_grid_styles() {
    ?>
    <style>
    /* Featured Image Styles */
    .inside-article {
        margin: 0 !important;
        padding: 0 !important;
    }

    .post-image-wrapper {
        position: relative;
        margin-bottom: 1em;
        background: #1b1b1b;
        border-radius: 5px;
        overflow: hidden;
    }

    .featured-image {
        display: block;
        position: relative;
        overflow: hidden;
        aspect-ratio: 16/9;
    }

    .featured-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .featured-image:hover img {
        transform: scale(1.05);
    }

    /* Overlay gradient for better text visibility */
    .featured-image::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%);
        z-index: 1;
    }

    /* Category link */
    .category-link {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #ff0000 !important;
        color: #fff !important;
        padding: 6px 12px !important;
        border-radius: 20px !important;
        font-size: 0.85em !important;
        z-index: 2;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        text-decoration: none !important;
        line-height: 1.2 !important;
        margin: 0 !important;
        border: none !important;
    }

    .category-link:hover {
        background: #cc0000 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    /* Post time */
    .post-time {
        position: absolute;
        bottom: 10px;
        left: 10px;
        color: #fff;
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 0.8em;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 5px;
        background: rgba(0, 0, 0, 0.5);
    }

    .post-time i {
        font-size: 0.9em;
    }

    /* Post views */
    .post-views {
        position: absolute;
        bottom: 10px;
        right: 10px;
        color: #fff;
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 0.8em;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .post-views i {
        font-size: 1em;
    }

    /* Post title */
    .entry-header {
        padding: 15px;
        background: #1b1b1b;
    }

    .entry-title {
        font-size: 1em;
        margin: 0;
        line-height: 1.4;
    }

    .entry-title a {
        color: #fff;
        text-decoration: none;
    }

    .entry-title a:hover {
        color: #ff0000;
    }

    /* Grid Layout for Homepage and Archive */
    .generate-columns-container {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 20px !important;
        margin: 0 0 2em 0 !important;
        padding: 0 !important;
        position: relative;
    }

    .generate-columns-container article {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        position: relative;
        background: #2d2d2d;
        border-radius: 5px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .generate-columns-container article:hover {
        transform: translateY(-5px);
    }

    .post-image-wrapper {
        position: relative;
        margin-bottom: 0 !important;
        background: #1b1b1b;
        border-radius: 5px 5px 0 0;
        overflow: hidden;
    }

    .featured-image {
        display: block;
        position: relative;
        overflow: hidden;
        aspect-ratio: 16/9;
    }

    .featured-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .featured-image:hover img {
        transform: scale(1.05);
    }

    .entry-header {
        padding: 15px;
        background: #2d2d2d;
        margin: 0 !important;
    }

    .entry-title {
        font-size: 1em;
        margin: 0;
        line-height: 1.4;
    }

    .entry-title a {
        color: #fff;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .entry-title a:hover {
        color: #ff0000;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .generate-columns-container {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }

        .category-link {
            font-size: 0.7em;
            padding: 3px 6px;
        }

        .post-time,
        .post-views {
            font-size: 0.65em;
            padding: 2px 5px;
        }

        .entry-title {
            font-size: 0.85em;
        }

        .entry-header {
            padding: 10px;
        }
    }

    /* Hide excerpt on grid layout */
    .entry-summary {
        display: none;
    }

    /* Related Posts Styling */
    .related-posts-section {
        margin: 2em 0;
        padding: 20px;
        background: #1b1b1b;
        border-radius: 10px;
    }

    .related-posts-title {
        font-size: 1.5em;
        margin-bottom: 1.5em;
        color: #fff;
        text-align: center;
        position: relative;
        padding-bottom: 15px;
    }

    .related-posts-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: #ff0000;
    }

    .related-posts-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .related-post {
        background: #2d2d2d;
        border-radius: 5px;
        overflow: hidden;
        transition: transform 0.3s ease;
        position: relative;
    }

    .related-post:hover {
        transform: translateY(-5px);
    }

    .related-post-link {
        display: block;
        text-decoration: none;
        color: inherit;
        height: 100%;
    }

    .related-post-thumbnail {
        position: relative;
        display: block;
        aspect-ratio: 16/9;
        overflow: hidden;
    }

    .related-post-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .related-post:hover .related-post-thumbnail img {
        transform: scale(1.05);
    }

    .related-post-title {
        padding: 15px;
        margin: 0;
        font-size: 0.9em;
        line-height: 1.4;
        color: #fff;
        background: #2d2d2d;
    }

    .related-post-title:hover {
        color: #ff0000;
    }

    .related-post-meta {
        position: absolute;
        bottom: 10px;
        left: 10px;
        right: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 2;
    }

    .related-post .post-time,
    .related-post .post-views {
        background: rgba(0, 0, 0, 0.5);
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 0.8em;
        color: #fff;
    }

    .related-post .category-link {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
    }

    /* Pagination Container */
    .pagination-container {
        width: 100%;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #333;
        position: relative;
        z-index: 2;
        clear: both;
        display: block;
        background: #1b1b1b;
        border-radius: 10px;
        padding: 20px;
    }

    /* Pagination Styling */
    .nav-links {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        min-height: 50px;
        width: 100%;
        margin: 0;
        padding: 0;
    }

    .page-numbers {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 15px;
        background: #2d2d2d;
        color: #fff !important;
        text-decoration: none;
        border-radius: 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin: 0;
    }

    .page-numbers.current {
        background: #ff0000;
        color: #fff;
        box-shadow: 0 2px 8px rgba(255,0,0,0.3);
    }

    .page-numbers:hover:not(.current) {
        background: #3d3d3d;
        transform: translateY(-2px);
    }

    .prev.page-numbers,
    .next.page-numbers {
        padding: 0 20px;
    }

    /* Load More Button Styling */
    .load-more-container {
        text-align: center;
        margin-top: 30px;
    }

    .load-more-related {
        display: inline-block;
        padding: 12px 30px;
        background: #ff0000;
        color: #fff;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 1em;
    }

    .load-more-related:hover {
        background: #cc0000;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(255,0,0,0.3);
    }

    .load-more-related:disabled {
        background: #666;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Search Form Styling - Updated */
    .header-search {
        padding: 20px;
        background: #1b1b1b;
        margin: 0 auto 30px;
        border-radius: 10px;
        max-width: 1200px;
    }

    .search-form-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .advanced-search-form {
        display: grid;
        grid-template-columns: 1fr auto auto;
        gap: 15px;
        align-items: center;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 12px 20px;
        border: 2px solid #333;
        border-radius: 25px;
        background: #2d2d2d;
        color: #fff;
        font-size: 1em;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #ff0000;
        box-shadow: 0 0 0 2px rgba(255,0,0,0.1);
    }

    .search-filters {
        display: flex;
        gap: 10px;
    }

    .search-filter-select {
        padding: 10px 15px;
        border: 2px solid #333;
        border-radius: 20px;
        background: #2d2d2d;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 150px;
    }

    .search-filter-select:focus {
        outline: none;
        border-color: #ff0000;
        box-shadow: 0 0 0 2px rgba(255,0,0,0.1);
    }

    .search-submit {
        padding: 12px 30px;
        background: #ff0000;
        color: #fff;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        min-width: 120px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .search-submit:hover {
        background: #cc0000;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(255,0,0,0.3);
    }

    @media (max-width: 768px) {
        .advanced-search-form {
            grid-template-columns: 1fr;
        }

        .search-filters {
            flex-wrap: wrap;
        }

        .search-filter-select {
            width: 100%;
            min-width: unset;
        }

        .search-submit {
            width: 100%;
        }

        .header-search {
            margin: 0 15px 30px;
        }
    }
    </style>
    <?php
}
add_action('wp_head', 'add_custom_grid_styles', 100);


