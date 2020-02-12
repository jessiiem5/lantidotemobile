<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
 
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'option_antimobile' );

/** MySQL database username */
define( 'DB_USER', 'option_usantimob' );

/** MySQL database password */
define( 'DB_PASSWORD', 'bKStQ2@%R7~,' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '+I65,lZ#LOwe#*`-wvKVOnQw*;RzgAH+L7gC{|Y1f|ry:[gn H<^vL@0xtbcr?ee');
define('SECURE_AUTH_KEY',  '8@0N1e|rTB-.|1.!zc@TT2b_6p-7B=McYT|f-FRH3jq!Kynzka4[j-- ][4Ibq}u');
define('LOGGED_IN_KEY',    'ysCToP(RhrO?~]-{aF+(+gActB[vp7f-j^C::zJU8XWf9gr2I|g.|Mq^SB+W$8w,');
define('NONCE_KEY',        'D3UlB>(B6R(KdhEgfTW3FCO:&%]7-jpT@D]N7~XKRk$9[U>-MOq}h(DP4h q+xfh');
define('AUTH_SALT',        '(=6(0[KNsDtI~=pjCx[bD, .x5J?wc)xmj(CGw-2k1]]stXY4 3i2Z7gOd {IoHV');
define('SECURE_AUTH_SALT', '|OdOvKw`nd$fR7>ya3L-<^IN1KN3lW|sGX{hhk~Ny9 KrMpjK`}_IhggSiO|r9>z');
define('LOGGED_IN_SALT',   'D*6^*}lN6+!YHw.q*4iYxC|H^Td+]F-q}r:(h._7/|aooDoW8f-+m_pPO*z[xqF)');
define('NONCE_SALT',       '#.+:Hsmq>2UhVac+{p a3Bi)(@+c;PQTGERT9P_O~&%JI{-W-=xj6M*9~s-h[MjU');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', true );

define( 'WP_MEMORY_LIMIT', '256M' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
