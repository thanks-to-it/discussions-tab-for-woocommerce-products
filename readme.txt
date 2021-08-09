=== Discussions Tab for WooCommerce Products ===
Contributors: algoritmika, anbinder, karzin
Tags: woocommerce, woocommerce comments, woocommerce reviews, woocommerce product discussions, comments, reviews, discussions, product, shop, ecommerce, comments tab, discussion tab, question and answer, product question, product support, tab, product comments, woo commerce
Requires at least: 4.4
Tested up to: 5.8
Stable tag: 1.3.6
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Creates a discussions tab for WooCommerce products.

== Description ==

**Discussions Tab for WooCommerce Products** plugin creates a discussions tab for WooCommerce Products.

In other words, this plugin allows to use the default WordPress **comment** system on a custom product tab.

Create a **support** tab and let customers ask questions about your WooCommerce products. Make them more satisfied replying their comments and answering their questions.

That will make your brand more credible and your customers more confident. As a result, you'll have more chances to increase your sales.

= Main Features =

* Customize **links**, **position** and **texts** (i.e. labels).
* Optionally allow **shortcodes** to be used inside discussion comments. Shortcodes can improve the way you communicate with your audience. Use videos, galleries, anything a shortcode can provide.
* Optionally **convert** your native WooCommerce reviews to discussions and vice versa. You can also filter your discussions comments in admin.
* Optionally **count replies** when counting discussions comments.

= Premium Version =

With [premium plugin version](https://wpfactory.com/item/discussions-tab-for-woocommerce-products/) you can:

* Use social networks like **Facebook** at your favor. Let your customers auto fill their names, e-mail and even get their Facebook profile picture with just one click.
* Decide if comments should be posted by anyone or only the ones **who bought the product**.
* **Notify** comment authors via **email** when they receive replies.
* **Label** if comments are being replied by **product authors** and/or **verified owners**.
* **Support** - We will be ready to help you in case of any issues or questions you may have.

= Feedback =

* We are open to your suggestions and feedback.
* Thank you for using or trying out one of our plugins!
* [Visit plugin site](https://wpfactory.com/item/discussions-tab-for-woocommerce-products/).

== Frequently Asked Questions ==

= How can I help translating it? =

You can do it through [translate.wordpress](https://translate.wordpress.org/projects/wp-plugins/discussions-tab-for-woocommerce-products).

= Is there a Pro version? =

Yes, it's located [here](https://wpfactory.com/item/discussions-tab-for-woocommerce-products/ "Discussions Tab for WooCommerce Products Pro").

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Discussions".

== Screenshots ==

1. Display a Discussions tab in your product page.

== Changelog ==

= 1.3.6 - 09/08/2021 =
* Fix - Comment edit redirect link from frontend.
* Fix - Display comment edit link on frontend only to admins.
* Fix - Remove space below comments.
* Dev - Emails - New comment email - Add new option to replace `#comments` anchor by discussions tab anchor.
* Dev - General - Comment meta - Add new option allowing admin to edit discussion comment user ID.
* Dev - General - Add new option to Show "edit comment link" only for users with the "moderate_comments" capability.

= 1.3.5 - 26/07/2021 =
* Tested up to: 5.8.
* WC tested up to: 5.4.
* Add github deploy setup.

= 1.3.4 - 26/07/2021 =
* Fix - Email - Manual email displays incorrect notices on admin.
* Fix - Labels - Some icons are not being displayed when using "Icons positioning" as "Next to comment author".
* Dev - Email - Add option to show labels next to comment author name.
* Refactor plugin.
* Rearrange admin settings.

= 1.3.3 - 14/07/2021 =
* Fix - Free and pro plugins can't be active at the same time.
* Dev - Use wpf-promoting-notice library to add notice on settings page regarding pro version.
* Dev - Labels - Add option to manage the icons positioning.
* Dev - General - Add option to edit discussion comment parent ID.
* Dev - General - Add option to edit discussion comment post ID.
* Dev - General - Add option to send manual notification to product author.
* Dev - General - Add option to remove undesired texts from notification.
* Dev - General - Add option to unsubscribe from notifications.
* Dev - General - Add option to enable/disable email notification.
* Rearrange admin settings.

= 1.3.2 - 29/06/2021 =
* Fix - `flush_rewrite_rules` doesn't work after saving the admin discussions page.
* Dev - General - Add option to remove content from comments.
* Tested up to: 5.7.
* WC tested up to: 5.4.

= 1.3.1 - 01/04/2021 =
* Fix - General - Rich text editor - Fix line breaks.
* Fix - Possible PHP warning Trying to get property 'comment_type' of non-object.
* Dev - General - Create "Custom sanitization" option for discussion comments.
* Improve plugin description on readme.

= 1.3.0 - 11/03/2021 =
* Fix - Wrong text domain in some areas.
* Fix - General - Rich text editor - Uncaught TypeError if "Disable the visual editor when writing" is activated.
* Fix - Iconpicker icons on admin scroll to top when selected.
* Dev - Labels - Add "Title" option for product authors and support reps labels.
* Dev - Labels - Add "Text color" option for all the possible labels.
* Dev - Labels - Remove tip checkbox option allowing it to be disabled by leaving the tip text empty.
* Dev - Labels - Allow to disable the icon by leaving the icon empty.

= 1.2.9 - 05/03/2021 =
* Fix - Labels - Support Reps - Replace support reps ajax field by a textarea field.
* Dev - My account tab - Save permalinks on settings saving and on plugin update.

= 1.2.8 - 03/03/2021 =
* Fix - Scroller doesn't go to the correct position sometimes.
* Dev - Show blink effect on anchor target comment.

= 1.2.7 - 02/03/2021 =
* Dev - Load labels js dynamically.
* Dev - Labels - Add My Account tab option allowing to setup the support reps on My Account page.
* Remove old css.
* Modules with more readable names.

= 1.2.6 - 01/03/2021 =
* Fix - Close button from comment doesn't close the comment and goes to a wrong link.
* Dev - Change free version admin notice regarding Pro version.
* Dev - Refactor javascript using Webpack.
* Dev - General - Add "TinyMCE on discussion comments" option.

= 1.2.5 - 26/02/2021 =
* Fix - Possible timeout problem with a high amount of comments.

= 1.2.4 - 24/02/2021 =
* Fix - General - Ajax tab - Labels can't get displayed.
* Dev - Labels - Add "Support Reps" option.
* Dev - General - Add "Comment form position" option.

= 1.2.3 - 17/02/2021 =
* Dev - General - Add option to force open comments for product post type.
* Dev - General - Add option to load the discussions tab content via AJAX.
* WC tested up to: 5.0.
* Tested up to: 5.6.
* Improve general settings section.

= 1.2.2 - 08/10/2020 =
* Dev - Core - `handle_shortcodes()` - Third (unused) param removed (`$args`).
* WC tested up to: 4.5.
* Tested up to: 5.5.

= 1.2.1 - 03/08/2020 =
* Dev - General - Extra Options - Notify authors - Removing "In reply to..." and "IP address..." patterns from the email content now.
* Tested up to: 5.4.
* WC tested up to: 4.3.

= 1.2.0 - 05/01/2020 =
* Dev - Texts - "Tab title" option added.
* Dev - Texts - "Textarea placeholder" option added.
* Dev - Template - textarea ID set to `discussion`.
* Dev - Template - `id_form` set to `discussionform`.
* Dev - Template - `id_submit` set to `submit_discussion`.
* Dev - Template - Unnecessary space symbol removed.

= 1.1.1 - 03/01/2020 =
* Fix - Missing CSS file error fixed.
* Dev - Admin settings descriptions updated.
* Dev - Code refactoring.

= 1.1.0 - 21/11/2019 =
* Fix - Labels - "Load Font Awesome" option fixed.
* Fix - Labels - "Label color" options fixed.
* Fix - Some translations fixed.
* Dev - General - "Tab position" option added.
* Dev - "Comments > Comment type" column moved to the free plugin version.
* Dev - "General > Tab link" and "General > Comment link" options moved to the free plugin version.
* Dev - "General > Shortcodes" options moved to the free plugin version.
* Dev - "Conversions" moved to the free plugin version (and "Advanced > Conversions" option added).
* Dev - Labels - Loading latest Font Awesome v5.11.2 now.
* Dev - Labels - Authors - "Reviews label" option removed.
* Dev - General - Shortcodes - "Enable in reviews" option removed.
* Dev - Admin settings restyled; descriptions updated.
* Dev - Major code refactoring.
* Tested up to: 5.3.
* WC tested up to: 3.8.

= 1.0.8 - 12/02/2018 =
* Make the plugin compatible with WooCommerce 3.3.1.

= 1.0.7 - 16/08/2017 =
* Fix metabox about pro version.
* Create option to notify comment authors when they receive replies.

= 1.0.6 - 07/08/2017 =
* Create option to add author tag to reviews.
* Create filter `alg_dtwp_filter_tab_id` to change tab slug.
* Create filter `alg_dtwp_filter_comment_link` to change comment link.
* Fix tab css.

= 1.0.5 - 04/08/2017 =
* Add one more default class (`commentlist`) to comments lists.
* Make Comment list classes unique.
* Add more callback functions to different themes.
* Create new section on admin (advanced).
* Create new option to get the `wp_list_comments` `callback` argument.
* Fix `alg_dtwp_comment_tags` filter.
* Create a tip for "verified owners label".
* Customize label icons using font awesome.
* Create an option to manage authors labels.
* Make labels more customizable.

= 1.0.4 - 03/08/2017 =
* Remove comments counting test on template for closed comments.
* Create a new filter `alg_dtwp_wp_list_comments_args` to filter arguments passed to `wp_list_comments`.
* Fix bug when posting new discussions or reviews.
* Customize verified owner label color.
* Add a filter to create tags on comments (`alg_dtwp_comment_tags`).

= 1.0.3 - 03/08/2017 =
* Add a new string when there are no discussions yet.
* Fix bug when posting new discussions.
* Add option to change discussions tab link.
* Add option to change discussions comment link.
* Fix facebook app ID.

= 1.0.2 - 02/08/2017 =
* Fix discussions and reviews counting.
* Fix discussions tab opening.
* Add option to count replies or not.
* Add new options to handle verified owners.
* Add a new string when there are no discussions yet.

= 1.0.1 - 27/07/2017 =
* Fix Hub theme comment type.
* Include info and images about the pro version.
* Fix avatar image on reviews.
* Add option to control shortcodes in comments.

= 1.0.0 - 18/07/2017 =
* Initial release.

== Upgrade Notice ==

= 1.1.0 =
This is the first release of the plugin.