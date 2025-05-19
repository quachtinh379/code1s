<?php
/**
 * The template for displaying the header.
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body <?php body_class(); ?> <?php generate_do_microdata( 'body' ); ?>>
	<?php
	/**
	 * wp_body_open hook.
	 *
	 * @since 2.3
	 */
	do_action( 'wp_body_open' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core WP hook.

	/**
	 * generate_before_header hook.
	 *
	 * @since 0.1
	 *
	 * @hooked generate_do_skip_to_content_link - 2
	 * @hooked generate_top_bar - 5
	 * @hooked generate_add_navigation_before_header - 5
	 */
	do_action( 'generate_before_header' );

	/**
	 * generate_header hook.
	 *
	 * @since 1.3.42
	 *
	 * @hooked generate_construct_header - 10
	 */
	do_action( 'generate_header' );

	/**
	 * generate_after_header hook.
	 *
	 * @since 0.1
	 *
	 * @hooked generate_featured_page_header - 10
	 */
	do_action( 'generate_after_header' );
	?>

	<div class="header-search">
		<div class="search-form-container">
			<form role="search" method="get" class="advanced-search-form" action="<?php echo esc_url(home_url('/')); ?>">
				<div class="search-input-wrapper">
					<input type="search" class="search-input" placeholder="Tìm kiếm..." value="<?php echo get_search_query(); ?>" name="s" />
				</div>
				
				<div class="search-filters">
					<?php
					// Dropdown chuyên mục
					$categories = get_categories(array('hide_empty' => true));
					if (!empty($categories)) : ?>
						<select name="category" class="search-filter-select">
							<option value="">Tất cả chuyên mục</option>
							<?php foreach ($categories as $category) : ?>
								<option value="<?php echo esc_attr($category->term_id); ?>" <?php selected(isset($_GET['category']) && $_GET['category'] == $category->term_id); ?>>
									<?php echo esc_html($category->name); ?>
								</option>
							<?php endforeach; ?>
						</select>
					<?php endif; ?>

					<!-- Lọc theo lượt xem -->
					<select name="views" class="search-filter-select">
						<option value="">Sắp xếp theo lượt xem</option>
						<option value="high" <?php selected(isset($_GET['views']) && $_GET['views'] == 'high'); ?>>Cao nhất</option>
						<option value="low" <?php selected(isset($_GET['views']) && $_GET['views'] == 'low'); ?>>Thấp nhất</option>
					</select>

					<!-- Lọc theo thời gian -->
					<select name="date" class="search-filter-select">
						<option value="">Tất cả thời gian</option>
						<option value="today" <?php selected(isset($_GET['date']) && $_GET['date'] == 'today'); ?>>Hôm nay</option>
						<option value="week" <?php selected(isset($_GET['date']) && $_GET['date'] == 'week'); ?>>7 ngày qua</option>
						<option value="month" <?php selected(isset($_GET['date']) && $_GET['date'] == 'month'); ?>>30 ngày qua</option>
						<option value="year" <?php selected(isset($_GET['date']) && $_GET['date'] == 'year'); ?>>365 ngày qua</option>
					</select>
				</div>

				<button type="submit" class="search-submit">
					<i class="fas fa-search"></i>
					<span>Tìm kiếm</span>
				</button>
			</form>
		</div>
	</div>

	<div <?php generate_do_attr( 'page' ); ?>>
		<?php
		/**
		 * generate_inside_site_container hook.
		 *
		 * @since 2.4
		 */
		do_action( 'generate_inside_site_container' );
		?>
		<div <?php generate_do_attr( 'site-content' ); ?>>
			<?php
			/**
			 * generate_inside_container hook.
			 *
			 * @since 0.1
			 */
			do_action( 'generate_inside_container' );
