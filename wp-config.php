<?php




/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
// define( 'DB_NAME', 'code1s' );

// /** Database username */
// define( 'DB_USER', 'code1s_db' );

// /** Database password */
// define( 'DB_PASSWORD', 'Bao940822@1' );

// /** Database hostname */
// define( 'DB_HOST', 'localhost' );

define('DB_NAME', 'local');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_HOST', 'localhost');

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'OET=yGk#&5P`l0:h3qB3HBl}hQ@}KJQtdzdZ&$)K0n4y}>v_Pn(;[{1<or9oY>:G' );
define( 'SECURE_AUTH_KEY',  'v?/72+!+mI)+azpQ/Og-6:ul&%g6BJj_JbMjKt`Y@<;$ U0ylySpU*a(e/@-(Ta7' );
define( 'LOGGED_IN_KEY',    ',3dEEi]b{:2]I8MU=2Txlr_`X/4C$JHhQvXc!Mt@>1uv yaIW]}92L%AFv/{q=Ex' );
define( 'NONCE_KEY',        ')ZsNL2]6Rp: 0x+X+.EYju)eg,64ZQeHmftLB$:3ROHdpj_*Mq0pzB+s,-$p,XlP' );
define( 'AUTH_SALT',        'K`Zs<SJ6g6>^Cd7s]{(w%,9)ZNE-8|rcP@klx;34Q^3|:nL;Z<^<Of}6`]~G}l9^' );
define( 'SECURE_AUTH_SALT', 'Cq[[_-{g1+G*-$}u$%a8+w?gf:_~8c^H>tuzu#jO&H}kYde/apCa0R7#7ef ;*[J' );
define( 'LOGGED_IN_SALT',   'Pkz,r1,#~qkJiMeUv]{Y8h]Ied.G+L!llKf-J4v[#F-L5~g6y`?GH8d1(IvpwEAO' );
define( 'NONCE_SALT',       '}S.:R6yR=Q+EfGxoAR4MJx4z_h5%|etuR m]5Z4<uq=,R6aCVzXr+3Qt?m5Aq|Ah' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
