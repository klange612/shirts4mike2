<?php 
/*
 * create the correct number of page navigation items for the navigation
 * and the html to display it correctly.
 * if statement set the page you are on to a span value and not a link
 */
?>
<div class="pagination">
<?php if ($pages > 1) {
	for ($x = 1; $x <= $pages; $x++) {
		if ($x == $current_page) { ?>
								<span><?php echo $x ?></span>
						<?php 	} else {  ?>				
								<a href="?pg=<?php echo $x; ?>"><?php echo $x; ?></a>
						<?php } ?>
					<?php } ?>
					<?php } ?>
				</div>
	