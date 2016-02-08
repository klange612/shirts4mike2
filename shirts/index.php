<?php 
$pageTitle = "Mike's Full Catalog of Shirts";
$section = "shirts";

require_once('../inc/config.php');
require_once(ROOT_PATH . "inc/header.php"); 
include(ROOT_PATH . "inc/products.php");

$pages = get_products_pages();

if (isset($_GET["pg"])) {
	$current_page = $_GET["pg"];
	$products = get_products_subset($current_page);
} else {
	$current_page = 1;
	$products = get_products_subset($current_page);
}

if ($current_page > $pages) {
	header("Location: ./?pg=" . $pages);
}

if ($current_page < 1) {
	Header("Location: ./");
}

?>
		<div class="section shirts page">

			<div class="wrapper">

				<h1>Mike&rsquo;s Full Catalog of Shirts</h1>
				
				<div class="pagination">
					<?php if ($pages > 1) { 
						for ($x = 1; $x <= $pages; $x++) {
						?>				
								<a href="?pg=<?php echo $x; ?>"><?php echo $x; ?></a>
						<?php } ?>
					<?php } ?>
				</div>
				
				<ul class="products">
					<?php foreach($products as $product) { 
							echo get_list_view_html($product);
						}
					?>
				</ul>

			</div>

		</div>

<?php include(ROOT_PATH . 'inc/footer.php') ?>