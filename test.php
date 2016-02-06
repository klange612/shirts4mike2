<?php
include("inc/products.php");

foreach ($products as $product_id => $prod) {
	var_dump($product_id);
	echo "</br> data ";
//	echo $prod["name"];
	var_dump($prod);
	echo "</br>";
}