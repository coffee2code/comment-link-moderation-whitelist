=== Comment Link Moderation Whitelist ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: comment, moderation, comment_max_links, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.7
Tested up to: 4.9
Stable tag: 1.0

Allows for whitelisted URLs to not count against the comment moderation max number of links limit.

== Description ==

Allows for whitelisted URLs to not count against the comment moderation max number of links limit.

On the 'Settings' -> 'Discussion' admin page, WordPress allows admins to define the maximum number of links permitted in a comment before that number of links automatically triggers moderation for a comment. That setting is labeled "Hold a comment in the queue if it contains N or more links" under "Comment Moderation", where "N" is the input for the specified number. If set, WordPress will blindly count any link that appears in the comment and hold that comment for moderation if enough links are found.

In certain cases, however, you'd like to your visitors to be able to link to certain trusted URLs (such as your own site) without it counting towards the moderation link count limit. Unnecessary moderation of comments that simply contain links to resources you trust puts additional burden on comment moderators and hampers discussions.

Simply install this plugin and specify a list of trusted domains, and then links to those URLs will no longer count against your commenters.

Note: It is unnecessary to specify the protocol when listing URLs. Also, subdomains are inferred and need not be explicitly listed.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/comment-link-moderation-whitelist/) | [Plugin Directory Page](https://wordpress.org/plugins/comment-link-moderation-whitelist/) | [Author Homepage](http://coffee2code.com)


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

= Does this plugin include unit tests? =

Yes.

= Is this plugin localizable? =

Yes.


== Changelog ==

= 1.0 (2018-01-19) =
* Initial public release


== Upgrade Notice ==

= 1.0 =
Initial public release.
