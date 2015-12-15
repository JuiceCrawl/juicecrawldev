<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'juicecrawl_com');

/** MySQL database username */
define('DB_USER', 'juicecrawlcom');

/** MySQL database password */
define('DB_PASSWORD', '^G*wAwRr');

/** MySQL hostname */
define('DB_HOST', 'mysql.juicecrawl.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'XMGLntAo39OV!M`j?(5FK+6eAKWYfYVKaekjV7W*ut|*u^dle+G9I?$H_?NfkyvO');
define('SECURE_AUTH_KEY',  '%azp&)g/KNs/7F8u$4)&ig2rg;1im7Hv;BNcd5s&F$(|mEKwktoEqMB3B4*~I5G!');
define('LOGGED_IN_KEY',    'E#op%Ed:*:q;TJ/E!4Cj5xwrW&aX*&L|J%K_GRV/y33#RiJw:fd$##am3a"btIp~');
define('NONCE_KEY',        'H!fSvWx*9rcN+^Nuqh46fiIlss1;c2NDKmNI0YIy6"CIC:ux3ui^pWpBzqUDWD5X');
define('AUTH_SALT',        '!;@*MRcZS_"Ihz??(QFAi/tB55:cBZFkkf76x8I_nQdJL++i*iP"8X*/_XZs7N1b');
define('SECURE_AUTH_SALT', 'Mn;Dt)%HMe%l/!8Y^&Y8Mb?($p2+S+enng`_nM)bM!)(G7k9U5e_/+5I47yqj!l9');
define('LOGGED_IN_SALT',   '`LLs6!cA#68EyogUG^i3Ga_"#5"aF16Yf!4?pwmXaUr3xQi1~3gJ?|1s!to^vRq#');
define('NONCE_SALT',       '5BaKa~0aFnnIZHhc0IC;oA4B_RTB))"avltt3L;iHBl4v`u+6WuVBdyjVR~QD"ae');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_sj7eui_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

