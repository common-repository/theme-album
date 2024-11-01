=== Theme Album ===
Contributors: johnh10
Plugin Name: Theme Album
Plugin URI: http://www.superblogme.com/theme-album/
Tags: theme, album, grid, list, display, loop, main loop, gallery, thumbnails,
tube
Requires at least: 3.0.1
Tested up to: 4.0
Stable Tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Have any theme automatically display posts as a grid or list album using thumbnails.

== Description ==

Replace the default WordPress loop in your theme with a list or grid album. It
will automatically create album thumbnails from each post looking in this
order:

1.        The featured image.
2.        The first image found in the content.
3.        The first attached image.
4.        The default image.

Any images from the Media Library will be automatically resized if needed to a
new thumbnail to save resources. Remote images will be mock sized to given
dimensions.

== Installation ==

1. Install the plugin through WordPress admin or upload the 'Theme Album'
directory to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit 'Settings -> Theme Album' to set default options.
4. There is no 'hook' to change the WordPress loop, so we'll need to edit the
template index file with 2 simple changes.

The typical WordPress loop starts here:

`<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>`


and ends here:

`<?php endwhile; else : ?>
        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>`

Change the loop to this:

`<?php if (isset($theme_album)) : $theme_album->ta_albumFormat(); else : ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>`

and :

`<?php endwhile; else : ?>
        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>
<?php endif; ?>`

5. Tweak the custom CSS in Settings to match your site if needed.


== Screenshots ==

1. Posts displayed in a grid album format
2. Posts displayed in a list album format


== Changelog ==

= 1.1.1 =

* Remove any unsafe characters when doing media library check.

= 1.1 =

* Use base URL of image in post content if from an auto-generated thumbnail


= 1.0 =

* Initial Release
