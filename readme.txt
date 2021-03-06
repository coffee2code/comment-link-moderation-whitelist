=== Comment Link Moderation Whitelist ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: comment, moderation, comment_max_links, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.7
Tested up to: 5.4
Stable tag: 1.1.3

Allows for whitelisted URLs to not count against the comment moderation max number of links limit.

== Description ==

Allows for whitelisted URLs to not count against the comment moderation max number of links limit.

On the 'Settings' -> 'Discussion' admin page, WordPress allows admins to define the maximum number of links permitted in a comment before that number of links automatically triggers moderation for a comment. That setting is labeled "Hold a comment in the queue if it contains N or more links" under "Comment Moderation", where "N" is the input for the specified number. If set, WordPress will blindly count any link that appears in the comment and hold that comment for moderation if enough links are found.

In certain cases, however, you'd like to your visitors to be able to link to certain trusted URLs (such as your own site) without it counting towards the moderation link count limit. Unnecessary moderation of comments that simply contain links to resources you trust puts additional burden on comment moderators and hampers discussions.

Simply install this plugin and specify a list of trusted domains, and then links to those URLs will no longer count against your commenters.

Note: It is unnecessary to specify the protocol when listing URLs. Also, subdomains are inferred and need not be explicitly listed.

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/comment-link-moderation-whitelist/) | [Plugin Directory Page](https://wordpress.org/plugins/comment-link-moderation-whitelist/) | [GitHub](https://github.com/coffee2code/comment-link-moderation-whitelist/) | [Author Homepage](https://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or unzip `comment-link-moderation-whitelist.zip` inside the plugins directory for your site (typically `/wp-content/plugins/`).
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Via the 'Settings' -> 'Discussion' admin page, define some URLs in the 'Comment Link Moderation Whitelist' field.


== Screenshots ==

1. A screenshot of the `Settings` -> `Discussion` admin page showing the 'Comment Link Moderation Whitelist' input field.


== Frequently Asked Questions ==

= Do I need to explicitly list all the subdomains of a domain? =

No, not if you list the primary domain name. If you list <em>example.com</em>, then there is no need to explicit list its subdomains, such as <em>info.example.com</em>. However, if you do not list the primary domain (in cases where you consider it too broad), then yes, you must list each subdomain you want to whitelist.

= Do I need to specify the protocol when listing a URL? =

No. You can omit the "http://" and "https://" from the URLs you list, though it won't matter if you include it.

= Can I specify a path to only whitelist a certain section of a URL? =

Yes, you can input something like "example.com/docs/" to allow only links relative to the location. In such a case, "example.com" and "example.com/downloads" would not be  whitelisted and would count against the comment moderation max number of links limit.

= Does this completely negate any checking for the number of links in a comment if a commenter uses an excessive number of whitelisted URLs? =

No. There is a hardcoded maximum number of comment links limit of 25 (which will become a configurable setting in a future release). If the number of comment links exceeds this number, regardless of whatever URLs are whitelisted, then the comment will be flagged for moderation. In the event WordPress is given a comment max links limit greater than 25, then the fallback maximum for whitelisted + non-whitelisted URL will be 10 higher than that limit. This limit exists to prevent abuse by a commenter including an excessive number of whitelisted URLs.

= Does this plugin include unit tests? =

Yes.

= Is this plugin localizable? =

Yes.


== Changelog ==

= 1.1.3 (2020-05-12) =
* Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS

= 1.1.2 (2019-12-22) =
* Docs fix: Use full path to CHANGELOG.md in the Changelog section of readme.txt
* Change: Note compatibility through WP 5.3+
* Change: Update copyright date (2020)

= 1.1.1 (2019-06-11) =
* New: Add CHANGELOG.md file and move all but most recent changelog entries into it
* Change: Update unit test install script and bootstrap to use latest WP unit test repo
* Change: Note compatibility through WP 5.2+

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/comment-link-moderation-whitelist/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 1.1.3 =
Trivial update: Updated a few URLs to be HTTPS and noted compatibility through WP 5.4+.

= 1.1.2 =
Trivial update: noted compatibility through WP 5.3+ and updated copyright date (2020)

= 1.1.1 =
Trivial update: modernized unit tests, created CHANGELOG.md to store historical changelog outside of readme.txt, noted compatibility through WP 5.2+

= 1.1 =
Minor update: tweaked plugin initialization, noted compatibility through WP 5.1+, and updated copyright date (2019)

= 1.0 =
Initial public release.
