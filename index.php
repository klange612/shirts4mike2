<?php 
$pageTitle = "Unique T-shirts designed by a frog";
$section = "home";

require_once("inc/config.php");
include(ROOT_PATH . "inc/header.php"); 
?>

		<div class="section banner">

			<div class="wrapper">

				<img class="hero" src="<?php echo BASE_URL; ?>img/mike-the-frog.png" alt="Mike the Frog says:">
				<div class="button">
					<a href="<?php echo BASE_URL; ?>shirts.php">
						<h2>Hey, I&rsquo;m Mike!</h2>
						<p>Check Out My Shirts</p>
					</a>
				</div>
			</div>

		</div>

		<div class="section shirts latest">

			<div class="wrapper">

				<h2>Mike&rsquo;s Latest Shirts</h2>

				<?php 
					include(ROOT_PATH . "inc/products.php"); 
					$products = get_products_all();
				?>
				<ul class="products">
					<?php 
						$list_view_html = get_products_recent($products);
						echo $list_view_html;
					?>								
				</ul>

			</div>

		</div>

<?php include(ROOT_PATH . 'inc/footer.php') ?>