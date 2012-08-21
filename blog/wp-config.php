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

define('WP_HOME', 'http://inlandempirecenter.org/blog');
define('WP_SITEURL', 'http://inlandempirecenter.org/blog');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'newiec');

/** MySQL database username */
define('DB_USER', 'roseinstitute1');

/** MySQL database password */
define('DB_PASSWORD', 'R0$ebeforehoes');

/** MySQL hostname */
define('DB_HOST', 'mysql.inlandempirecenter.org');

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
define('AUTH_KEY',         'GPU|,bdr .-)JfYw`4)kTkp^Wj?7?h}hX5h9mr8rlOERV38,.x=1?*} nNA.Z}+~');
define('SECURE_AUTH_KEY',  'Ab576gJ;-PxUpH~?Poe|^I$HyD,.)~1$Gr-IaS^ fykWcP<>`m ,7X3t-dMCJ- o');
define('LOGGED_IN_KEY',    'e4-VlaL_#9t8hf~QS!FTAb,yCpUz._0W^GlVUM8$NdVtnDfB1*DN^7J.s_i 5.9>');
define('NONCE_KEY',        'ez}:G _sz6X6a*1]}>GSDIMkLIx#=H.i7?U)JVQHty}(;KKjnVls-I7cuVUsbLH+');
define('AUTH_SALT',        'T%d+H/xH]_= ~SU-I9_QBtc:PD1p&<Ctci6 PKO*7D)gY:PHLr]&IBQOi:!4><0J');
define('SECURE_AUTH_SALT', 'r4%/Y[kT&D:CebxC%TA!k!%xI}ED<8W@J!c4Vl>b{7laa#v&#mF:/U5YJLLm-NQ9');
define('LOGGED_IN_SALT',   'tlH+8~g_a3 B2||1Col=/+J:@lH+nGuw^dMlTEzQA(MK>V/y-5I e |XK&4:Tvbs');
define('NONCE_SALT',       'KF@jlIt$C.UW(90=$M.kU-b-J~0^=:kALW`Kc7w h<W|}y+SywD%|[Ad<gE|KMN/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
