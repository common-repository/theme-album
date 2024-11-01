<div class="wrap theme-album">
    <h2>Theme Album v<?php echo THEME_ALBUM_VERSION; ?></h2>
	<div class='theme-album-buttons'>
        <a target='_blank' href="http://wordpress.org/support/plugin/theme-album" class="button-primary">Support Forum</a>
        <a target='_blank' href="http://wordpress.org/support/view/plugin-reviews/theme-album#postform" class="button-primary">Leave a review</a>
	</div>
Have your theme display posts as a grid or list album.
</div>
<div class="wrap theme-album border">
	<h3>Main Settings</h3>
    <form method="post" action="options.php"> 
<?php settings_fields('theme-album-group'); ?>

<?php
                $gridcheck = ( get_option('ta_format') == "grid" )  ? "checked" : "";
                $listcheck = ( get_option('ta_format') == "list" )  ? "checked" : "";
		$gridImg = THEME_ALBUM_URL . "images/ta_grid.jpg";
		$listImg = THEME_ALBUM_URL . "images/ta_list.jpg";
		$defaultImg = get_option('ta_default');
?>

<table class="form-table">  
    <tr valign="top">
	<th scope="row"><label for="ta_format">Theme Album Format:</label></th>
	<td>
		<img src='<?php echo $gridImg;?>' width='100' height='70' alt="grid album" />
                <input name="ta_format" type="radio" value="grid" <?php echo $gridcheck; ?>>grid</input>
		&nbsp; &nbsp; &nbsp; 
		<img src='<?php echo $listImg;?>' width='100' height='70' alt="list album" />
                <input name="ta_format" type="radio" value="list" <?php echo $listcheck; ?>>list</input>
	</td>
    </tr><tr valign="top">
	<th scope="row"><label for="ta_format">Thumbnail Dimensions:</label></th>
	<td>
		Width
		<input type="text" size="4" maxwidth="4" name="ta_width" id="ta_width" value="<?php echo get_option('ta_width'); ?>" />
		Height
		<input type="text" size="4" maxwidth="4" name="ta_height" id="ta_height" value="<?php echo get_option('ta_height'); ?>" />
	</td>
    </tr><tr valign="top">
	<th scope="row"><label for="ta_format">Default Thumbnail Image:</label></th>
	<td>
		<input type="text" size="80" name="ta_default" id="ta_default" value="<?php echo get_option('ta_default'); ?>" />
		<p/>
		<img src='<?php echo $defaultImg;?>' width='100' height='75' alt="default image" />
	</td>
    </tr><tr valign="top">
	<th scope="row"><label for="ta_custom_css">Theme Album Custom CSS:</label></th>
	<td>
<textarea rows="15" cols="100" name="ta_custom_css">
<?php echo get_option('ta_custom_css'); ?>
</textarea>
	</td>
    </tr>
</table>

	<hr />

        <?php submit_button(); ?>
    </form>
</div>

<div class="wrap theme-album border">
<h3>Integration instructions:</h3>
There is no 'hook' to change the <a target='_blank' href='http://codex.wordpress.org/The_Loop'>WordPress loop</a>, so we'll need to <em>edit the template index file</em> with 2 simple changes.

<p/>
The typical loop starts here:

<pre>&lt;?php if ( have_posts() )&nbsp;: while ( have_posts() )&nbsp;: the_post();&nbsp;?&gt;</pre>
and ends here:

<pre>&lt;?php endwhile; else&nbsp;:&nbsp;?&gt;
	&lt;p&gt;&lt;?php _e( 'Sorry, no posts matched your criteria.' );&nbsp;?&gt;&lt;/p&gt;
&lt;?php endif;&nbsp;?&gt;
</pre>

<strong>Change the loop to this:</strong>

<pre>
&lt;?php if (isset($theme_album)) : $theme_album->ta_albumFormat(); else : ?&gt;
&lt;?php if ( have_posts() )&nbsp;: while ( have_posts() )&nbsp;: the_post();&nbsp;?&gt;
</pre>

and :

<pre>
&lt;?php endwhile; else&nbsp;:&nbsp;?&gt;
	&lt;p&gt;&lt;?php _e( 'Sorry, no posts matched your criteria.' );&nbsp;?&gt;&lt;/p&gt;
&lt;?php endif;&nbsp;?&gt;
&lt;?php endif;&nbsp;?&gt;
</pre>

</div>
