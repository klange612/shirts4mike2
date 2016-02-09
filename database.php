<?php
/*
 * Setup database connection
 * server/host = localhost
 * database name/dbname = shirts4mike
 * username root
 * password "" = blank
 */
try {
	$db = new PDO("mysql:host=localhost;dbname=shirts4mike", "root", "");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->exec("SET NAMES 'utf8'");
// 	var_dump($db);
} catch(PDOException $e) {
	echo "$e->getMessage()";
	exit();
}
/*
 * query our database $db for all the 'name' and 'price' for items in products table of
 * database shirts4mike from $db
 */
try {
	$results = $db->query("SELECT name, price from products ORDER BY sku ASC");
	$all = $results->fetchAll();
	echo "Query successful";
	var_dump($all);
} catch(PDOException $e) {
	echo "Querey error $e";
	exit();
}
