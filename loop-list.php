<div class='theme-album'>
<?php
$postNum=1;
$imgWidth = $this->ta_width;
$imgHeight = $this->ta_height;
$dimension = ($imgWidth && $imgHeight) ? "width='$imgWidth' height='$imgHeight'" : "";
if (have_posts()) : while (have_posts()) : the_post();
	$divID = "thumbs_".$postNum++;
?>

	<div class='theme-album-list-box' id='<?php echo $divID;?>'>
		<a href="<?php the_permalink(); ?>">
		<img class='theme-album-list-thumb' alt='<?php the_title();?>' <?php echo $dimension; ?> src='<?php echo $this->ta_fetch_image();?>'>
		</a>
		<h3 class="theme-album-list-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
		<div class='theme-album-list-details'>
		<?php the_excerpt('Read the rest of this entry &raquo;'); ?>
		</div>
	</div>

<?php endwhile; ?>
<?php endif; ?>
</div> <!-- END theme-album-list-->
<div style='clear:both;'></div>
