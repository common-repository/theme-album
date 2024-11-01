<div class='theme-album'>
<?php
$postNum=1;
$imgWidth = $this->ta_width;
$imgHeight = $this->ta_height;
$boxWidth = $this->ta_width;
$boxHeight = $this->ta_height+50;	// set extra height to allow for details so all grids line up
$dimension = ($imgWidth && $imgHeight) ? "width='$imgWidth' height='$imgHeight'" : "";
$boxDimension = ($imgWidth && $imgHeight) ? "style='width:" . $boxWidth . "px;height:" . $boxHeight . "px;'" : "";
if (have_posts()) : while (have_posts()) : the_post();
	$divID = "thumbs_".$postNum++;
?>

	<div class='theme-album-grid-box' id='<?php echo $divID;?>' <?php echo $boxDimension;?>>
		<a href="<?php the_permalink(); ?>">
		<img class='theme-album-grid-thumb' alt='<?php the_title();?>' <?php echo $dimension; ?> src='<?php echo $this->ta_fetch_image();?>'>
		</a>
		<h3 class="theme-album-grid-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
		<div class='theme-album-grid-details'><?php the_time('F dS Y') ?></div>
	</div>

<?php endwhile; ?>
<?php endif; ?>
</div> <!-- END theme-album-grid -->
<div style='clear:both;'></div>
