<?php 
$pageTitle = "Mike's Full Catalog of Shirts";
$section = "shirts";

require_once('../inc/config.php');
require_once(ROOT_PATH . "inc/header.php"); 
include(ROOT_PATH . "inc/products.php");
?>
		<div class="section shirts page">

			<div class="wrapper">

				<h1>Mike&rsquo;s Full Catalog of Shirts</h1>

				<ul class="products">
					<?php foreach($products as $product) { 
							echo get_list_view_html($product);
						}
					?>
				</ul>

			</div>

		</div>

<?php include(ROOT_PATH . 'inc/footer.php') ?>