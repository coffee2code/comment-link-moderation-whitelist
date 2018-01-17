<?php
/**
 * Plugin Name: Comment Link Moderation Whitelist
 * Version:     1.0
 * Plugin URI:  http://coffee2code.com/wp-plugins/comment-link-moderation-whitelist/
 * Author:      Scott Reilly
 * Author URI:  http://coffee2code.com/
 * Text Domain: comment-link-moderation-whitelist
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Description: Tracks the user who trashed a post and when they trashed it. Displays that info as columns in admin trashed posts listings.
 *
 * Compatible with WordPress 4.8 through 4.9+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/comment-link-moderation-whitelist/
 *
 * @package Comment_Link_Moderation_Whitelist
 * @author  Scott Reilly
 * @version 1.0
 */

/*
 * TODO:
 */

/*
	Copyright (c) 2018 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_CommentLinkModerationWhitelist' ) ) :

class c2c_CommentLinkModerationWhitelist {

	/**
	 * Name for meta key used to store id of trashing user.
	 *
	 * @access private
	 * @var string
	 */
	private static $setting_name = 'c2c-comment-link-moderation-whitelist';

	/**
	 * Returns version of the plugin.
	 *
	 * @since 1.0
	 */
	public static function version() {
		return '1.0';
	}

	/**
	 * Hooks actions and filters.
	 *
	 * @since 1.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'do_init' ) );
	}

	/**
	 * Performs initializations on the 'init' action.
	 *
	 * @since 1.0
	 */
	public static function do_init() {
		// Load textdomain
		load_plugin_textdomain( 'comment-link-moderation-whitelist' );

		// Register hooks
		add_filter( 'comment_max_links_url', array( __CLASS__, 'comment_max_links_url' ), 10, 3 );
	}

	/**
	 * Adjust the max number of links permitted in comments to disregard the
	 * number of whitelisted links.
	 *
	 * @since 1.0
	 *
	 * @param int    $num_links The number of links found.
	 * @param string $url       Comment author's URL. Included in allowed links total.
	 * @param string $comment   Content of the comment.
	 * @return int
	 */
	public static function comment_max_links_url( $num_links, $url, $comment ) {
		// Get comment max links.
		$max_links = get_option( 'comment_max_links' );

		// Bail if the number of links doesn't exceed the maximum.
		if ( $num_links < $max_links ) {
			return $num_links;
		}

		// Get whitelisted URLs.
		$whitelist_urls = trim( get_option( self::$setting_name ) );

		// Bail if no whitelisted URLs are defined.
		if ( ! $whitelist_urls ) {
			return $num_links;
		}

		// URLs are defined one per line.
		$whitelist_urls = explode( "\n", $whitelist_urls );

		// Check if any whitelisted URLs are present in comment.
		foreach ( $whitelist_urls as $url ) {

			// Count the number of occurrences of this particular whitelisted URL.
			$num_whitelist_links = preg_match_all( '%<a [^>]*href=[\'\"]https?://[^/>]*' . preg_quote( $url, '%' ) . '%i', $comment, $out );

			// Increase the limit by the number of whitelisted URLs (so that they don't
			// count against the limit).
			$num_links -= $num_whitelist_links;

			// Stop if there are enough whitelisted links to bring the number of
			// non-whitelisted links below the max.
			if ( $num_links < $max_links ) {
				break;
			}

		}

		return $num_links;
	}

} // end c2c_CommentLinkModerationWhitelist

c2c_CommentLinkModerationWhitelist::init();

endif; // end if !class_exists()
