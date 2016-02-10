<?php
/*
 * Setup database connection
 * server/host = localhost
 * database name/dbname = shirts4mike
 * username root
 * password "" = blank
 */
try {
	$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "", DB_USER, DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->exec("SET NAMES 'utf8'");
} catch(PDOException $e) {
	echo "$e->getMessage()";
	exit();
}





/*
 * query our database $db for all the 'name' and 'price' for items in products table of
 * database shirts4mike from $db
 */
// try {
// 	$results = $db->query("SELECT * from products ORDER BY sku ASC");
// 	$products = $results->fetchAll(PDO::FETCH_ASSOC);
// 	echo "<pre>";
// 	var_dump($products);
// 	echo "</pre>";
// } catch(PDOException $e) {
// 	echo "Querey error $e";
// 	exit();
// }

// foreach ($products as $product) {
// 	echo $product["sku"];
// }
