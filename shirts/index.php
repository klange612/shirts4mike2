<?php 
$pageTitle = "Mike's Full Catalog of Shirts";
$section = "shirts";

require_once('../inc/config.php');
require_once(ROOT_PATH . "inc/header.php"); 
include(ROOT_PATH . "inc/products.php");

// assigns the number of pages our navigation will have in $pages from the function get_products_pages
$pages = get_products_pages();

// if GET[pg] is set assign current_page to GET[pg] and get subset of shirts to display
// if there is no GET[pg] then assign current_page = to page 1 and get the products for page 1
if (isset($_GET["pg"])) {
	$current_page = $_GET["pg"];
	$products = get_products_subset($current_page);
} else {
	$current_page = 1;
	$products = get_products_subset($current_page);
}
// if current_page has a value higher than the # of pages, set page to last page in navigation
if ($current_page > $pages) {
	header("Location: ./?pg=" . $pages);
}
// if current_page has a value less than 1, set current_page to 1
if ($current_page < 1) {
	Header("Location: ./");
}

?>
		<div class="section shirts page">

			<div class="wrapper">

				<h1>Mike&rsquo;s Full Catalog of Shirts</h1>
<!-- loads the navigation items				 -->
				<?php include(ROOT_PATH . "inc/list-navigation.html.php"); ?>
<!-- generates the shirts to display for the page				 -->
				<ul class="products">
					<?php foreach($products as $product) { 
							echo get_list_view_html($product);
						}
					?>
				</ul>

				<?php include(ROOT_PATH . "inc/list-navigation.html.php"); ?>

			</div>

		</div>

<?php include(ROOT_PATH . 'inc/footer.php') ?>