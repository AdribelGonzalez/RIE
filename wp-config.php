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
define('DB_NAME', 'db_9acd80_rie');

/** MySQL database username */
define('DB_USER', '9acd80_rie');

/** MySQL database password */
define('DB_PASSWORD', 'hola1234');

/** MySQL hostname */
define('DB_HOST', 'MYSQL5007.Smarterasp.net');

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
define('AUTH_KEY',         '14VbeE~cT(o6k`f6jb-]C aq,IEi5K7La~g+fX^yq4t9D5KR5SG$.3<J$n|q5|TY');
define('SECURE_AUTH_KEY',  '6{YSf|# lhuHVwPSomJZX67-P=RJH9&Fo1QTZ;igc#fniEMp/hI]0w^4w.t|(.TP');
define('LOGGED_IN_KEY',    'D{rqh=J1Bh)|&!?6h$(J+|[,eg&+6_Yr.=wVXcMvV36%J4U V<E%jLJh-(,?F3F-');
define('NONCE_KEY',        'bOm9}ELw}xek!2z+rMl%+ e-.I DlCp87,cszrOz6,;gj9KYkIs=K+trI4KL6k?|');
define('AUTH_SALT',        'gOEmO,^!u(ru }dKhyWVF?o>-0tbsb*^LBBo}KNjFY-!:n)}G)BV+R_!kMPHQfr4');
define('SECURE_AUTH_SALT', 'Pb#-V__BR3p_JHN(fY@a+oK#2L 0Adr+c4qfmC>L{H;Mi%O+WU_qIG/G~{hlcw@6');
define('LOGGED_IN_SALT',   'Uynj-XtfMgDN;1A1&,82}d-x;u:-^If=Wgz[#Av Y!YxL3wOi<|IDm QupGd(q1Y');
define('NONCE_SALT',       ']wM|^+_; +$0-Hp8M-M&5~q |h8~jRr5|B~^reZW+[OQnE#Oln~/W;!w&3^Ii:=!');

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
define('WPLANG', 'es_ES');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
define('WP_MEMORY_LIMIT', '128M');
?>