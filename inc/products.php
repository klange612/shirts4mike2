<?php

/*
 * get an individual product from the database
 * @param	sku		the sku we are searching for
 * @return	arrary	product array of elements or
 * 			false	if no matching product
 */
function get_products_single($sku) {
	include(ROOT_PATH . "inc/database.php");
	try {
		// next 2 steps, prepare and bindparam used to prevent sql injection
		// preapre the sql statement with ? as variable/placeholder
		$results = $db->prepare("SELECT * FROM products WHERE sku = ?");
		// bind the param # 1, the ? with the value of sku
		$results->bindParam(1, $sku);
		$results->execute(); // < executes our results query, which is an object
	} catch (Exception $e) {
		echo "Query error " . $e;
		exit();
	}
	$product = $results->fetch(PDO::FETCH_ASSOC);
	
	if ($product === false) return $product;
	
	$product["sizes"] = Array();
	
	try {
		$results = $db->prepare("
			SELECT size
			FROM products_sizes ps
			INNER JOIN sizes s on ps.size_id = s.id
			WHERE product_sku = ?
			ORDER by `order`");
		$results->bindParam(1, $sku);
		$results->execute();
	} catch (Exception $e) {
		echo "failed to get shirt size $e";
	}
	
	while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
		$product["sizes"][] = $row["size"];
	}
	return $product;
}

/*
 * get an individual product with the id provided
 * @param	int		$product_id		the number of the product to search for.  array key value.
 * @return	array	$product		the product that has the matching key from product_id
 */
function get_product($product_id) {
	$products = get_products_all();	
	foreach ($products as $product) {
		if ($product['sku'] == $product_id) {
			return $product;
		} else {
			return false;
		}
// 	if (isset($products[$product_id])) {
// 		$product = $products[$product_id];
// 	}
	}
}

/*
 * function to join the shirts to the size table
 */

function shirtSizes($sku) {
	try {
		$results->prepare("SELECT product_sku, size_id, size
		FROM products_sizes
		INNER JOIN sizes on products_sizes.size_id = sizes.id
		WHERE product_sku = ?
		ORDER by `order`");
		$results->bindParam(1, $sku);
		$results->execute();
	} catch (Exception $e) {
		echo "failed to get shirt size $e";
	}
	return $results->fetchAll(PDO::FETCH_ASSOC);
}
/*
 * Get the count/total # of items in the product catalog
 * @param	none
 * @return	int		count of products in the catalog
 */
function get_products_count() {
	return count(get_products_all());
}
/*
 * determines the number of pages our pagination will have
 * @param	none
 * @return	int		a ceilng value, number always rounded up, of the # of pages we'll have
 */
function get_products_pages() {
	return ceil(get_products_count()/PROD_DISPLAY);
// 	$total_shirts = get_products_count();
// 	if ($total_shirts % PROD_DISPLAY == 0) {
// 		return $total_shirts/PROD_DISPLAY;
// 	} else {
// 		return $total_shirts/PROD_DISPLAY + 1;
// 	}
}
/*
 * Gets a subset of the product catalog items.  the subset is the number of items to be 
 * displayed to the shirt pagination based on what is set in config.php PROD_DISPLAY
 * @param	int		$page	the page # we are on, so get the list for that page
 * @return	array			array of the # products that match PROD_DISPLAY quantity
 */
function get_products_subset($page) {
	$total_shirts = get_products_count();
	$products = get_products_all();
	$begin = (($page - 1) * PROD_DISPLAY);
	return array_slice($products, $begin, PROD_DISPLAY);
}
/*
 * Search a product from prodcuts name item for a search term $s
 * @param	string	the search term
 * @return	array	the array of all the products who's name matches the search term
 */
function get_products_search ($s) {
	$results = Array();
	$all = get_products_all();
	foreach ($all as $product) {
		if (stripos($product['name'], $s) !== false || $product['sku'] == $s) {
				$results[] = $product;	
		}
	}
	return $results;
}
/*
 * Gets the 4 most recent products in the product catalog
 * @param	none
 * @return	array	of the 4 most recent products
 */
function get_products_recent() {
	$all = get_products_all();
	$total_products = count($all);
	$position = 0;
	$list_view_html = "";
	foreach($all as $product) {
		$position = $position + 1;
		if ($total_products - $position < 4) {
			$list_view_html = get_list_view_html($product) . $list_view_html;
		}
	}
	return $list_view_html;
}
/*
 * creates the html display of each of the shirts in the list items<li> view for shirts
 * @param	array	$product	the product array that's used to build the individual list item
 * @return	html	$output		html to be displayed
 */
function get_list_view_html($product) {
    
    $output = "";

    $output = $output . "<li>";
    $output = $output . '<a href="' . BASE_URL . 'shirts/shirt.php?id=' . $product["sku"] . '">';
    $output = $output . '<img src="' . BASE_URL . $product["img"] . '" alt="' . $product["name"] . '">';
    $output = $output . "<p>View Details</p>";
    $output = $output . "</a>";
    $output = $output . "</li>";

    return $output;
}
/*
 * Builds the product catalog in array called products
 * @param    none
 * @return   array	An array list of all the products
	* server/host = localhost
	* database name/dbname = shirts4mike
	* username root
	* password "" = blank
 */
function get_products_all() {
	include(ROOT_PATH . "inc/database.php");
// 	try {
// 		$db = new PDO("mysql:host=localhost;dbname=shirts4mike", "root", "");
// 		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// 		$db->exec("SET NAMES 'utf8'");
// 	} catch(PDOException $e) {
// 		echo "$e->getMessage()";
// 		exit();
// 	}
	/*
	 * query our database $db for all the 'name' and 'price' for items in products table of
	 * database shirts4mike from $db
	 */
	try {
		$results = $db->query("SELECT * from products ORDER BY sku ASC");
		$products = $results->fetchAll(PDO::FETCH_ASSOC);
	} catch(PDOException $e) {
		echo "Querey error $e";
		exit();
	}
	return $products;	
}
/*
 * old version of the product catalog before moved to database
 */
//     $products = array();
//     $products[101] = array(
//     	"name" => "Logo Shirt, Red",
//     	"img" => "img/shirts/shirt-101.jpg",
//     	"price" => 18,
//     	"paypal" => "9P7DLECFD4LKE",
//         "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[102] = array(
//     	"name" => "Mike the Frog Shirt, Black",
//         "img" => "img/shirts/shirt-102.jpg",
//         "price" => 20,
//         "paypal" => "L7LP5BQCBXGGJ",
//         "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[103] = array(
//         "name" => "Mike the Frog Shirt, Blue",
//         "img" => "img/shirts/shirt-103.jpg",    
//         "price" => 20,
//         "paypal" => "UWQC93FQRTUS2",
//         "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[104] = array(
//         "name" => "Logo Shirt, Green",
//         "img" => "img/shirts/shirt-104.jpg",    
//         "price" => 18,
//         "paypal" => "YKVL5F87E8PCS",
//         "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[105] = array(
//         "name" => "Mike the Frog Shirt, Yellow",
//         "img" => "img/shirts/shirt-105.jpg",    
//         "price" => 25,
//         "paypal" => "4CLP2SCVYM288",
//         "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[106] = array(
//         "name" => "Logo Shirt, Gray",
//         "img" => "img/shirts/shirt-106.jpg",    
//         "price" => 20,
//         "paypal" => "TNAZ2RGYYJ396",
//         "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[107] = array(
//         "name" => "Logo Shirt, Teal",
//         "img" => "img/shirts/shirt-107.jpg",    
//         "price" => 20,
//         "paypal" => "S5FMPJN6Y2C32",
//         "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[108] = array(
//         "name" => "Mike the Frog Shirt, Orange",
//         "img" => "img/shirts/shirt-108.jpg",    
//         "price" => 25,
//         "paypal" => "JMFK7P7VEHS44",
//         "sizes" => array("Large","X-Large")
//     );
//     $products[109] = array(
//             "name" => "Get Coding Shirt, Gray",
//             "img" => "img/shirts/shirt-109.jpg",    
//             "price" => 20,
//             "paypal" => "B5DAJHWHDA4RC",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[110] = array(
//             "name" => "HTML5 Shirt, Orange",
//             "img" => "img/shirts/shirt-110.jpg",    
//             "price" => 22,
//             "paypal" => "6T2LVA8EDZR8L",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[111] = array(
//             "name" => "CSS3 Shirt, Gray",
//             "img" => "img/shirts/shirt-111.jpg",    
//             "price" => 22,
//             "paypal" => "MA2WQGE2KCWDS",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[112] = array(
//             "name" => "HTML5 Shirt, Blue",
//             "img" => "img/shirts/shirt-112.jpg",    
//             "price" => 22,
//             "paypal" => "FWR955VF5PALA",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[113] = array(
//             "name" => "CSS3 Shirt, Black",
//             "img" => "img/shirts/shirt-113.jpg",    
//             "price" => 22,
//             "paypal" => "4ELH2M2FW7272",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[114] = array(
//             "name" => "PHP Shirt, Yellow",
//             "img" => "img/shirts/shirt-114.jpg",    
//             "price" => 24,
//             "paypal" => "AT3XQ3ZVP2DZG",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[115] = array(
//             "name" => "PHP Shirt, Purple",
//             "img" => "img/shirts/shirt-115.jpg",    
//             "price" => 24,
//             "paypal" => "LYESEKV9JWE3A",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[116] = array(
//             "name" => "PHP Shirt, Green",
//             "img" => "img/shirts/shirt-116.jpg",    
//             "price" => 24,
//             "paypal" => "KT7MRRJUXZR34",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[117] = array(
//             "name" => "Get Coding Shirt, Red",
//             "img" => "img/shirts/shirt-117.jpg",    
//             "price" => 20,
//             "paypal" => "5UXJG8PXRXFKE",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[118] = array(
//             "name" => "Mike the Frog Shirt, Purple",
//             "img" => "img/shirts/shirt-118.jpg",    
//             "price" => 25,
//             "paypal" => "KHP8PYPDZZFTA",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[119] = array(
//             "name" => "CSS3 Shirt, Purple",
//             "img" => "img/shirts/shirt-119.jpg",    
//             "price" => 22,
//             "paypal" => "BFJRFE24L93NW",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[120] = array(
//             "name" => "HTML5 Shirt, Red",
//             "img" => "img/shirts/shirt-120.jpg",    
//             "price" => 22,
//             "paypal" => "RUVJSBR9FXXWQ",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[121] = array(
//             "name" => "Get Coding Shirt, Blue",
//             "img" => "img/shirts/shirt-121.jpg",    
//             "price" => 20,
//             "paypal" => "PGN6ULGFZTXL4",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[122] = array(
//             "name" => "PHP Shirt, Gray",
//             "img" => "img/shirts/shirt-122.jpg",    
//             "price" => 24,
//             "paypal" => "PYR4QH97W2TSJ",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[123] = array(
//             "name" => "Mike the Frog Shirt, Green",
//             "img" => "img/shirts/shirt-123.jpg",    
//             "price" => 25,
//             "paypal" => "STDAUJJTSPT54",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[124] = array(
//             "name" => "Logo Shirt, Yellow",
//             "img" => "img/shirts/shirt-124.jpg",    
//             "price" => 20,
//             "paypal" => "2R2U74KWU5RXG",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[125] = array(
//             "name" => "CSS3 Shirt, Blue",
//             "img" => "img/shirts/shirt-125.jpg",    
//             "price" => 22,
//             "paypal" => "GJG7F8EW3XFAS",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[126] = array(
//             "name" => "Doctype Shirt, Green",
//             "img" => "img/shirts/shirt-126.jpg",    
//             "price" => 25,
//             "paypal" => "QW2LFRYGU7L4Q",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[127] = array(
//             "name" => "Logo Shirt, Purple",
//             "img" => "img/shirts/shirt-127.jpg",    
//             "price" => 20,
//             "paypal" => "GFV6QVRMJU7F8",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[128] = array(
//             "name" => "Doctype Shirt, Purple",
//             "img" => "img/shirts/shirt-128.jpg",    
//             "price" => 25,
//             "paypal" => "BARQMHMB565PN",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[129] = array(
//             "name" => "Get Coding Shirt, Green",
//             "img" => "img/shirts/shirt-129.jpg",    
//             "price" => 20,
//             "paypal" => "DH9GXABU3P8GS",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[130] = array(
//             "name" => "HTML5 Shirt, Teal",
//             "img" => "img/shirts/shirt-130.jpg",    
//             "price" => 22,
//             "paypal" => "4LZ3EUVCBENE4",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[131] = array(
//             "name" => "Logo Shirt, Orange",
//             "img" => "img/shirts/shirt-131.jpg",    
//             "price" => 20,
//             "paypal" => "7BNDYJBKWD364",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );
//     $products[132] = array(
//             "name" => "Mike the Frog Shirt, Red",
//             "img" => "img/shirts/shirt-132.jpg",    
//             "price" => 25,
//             "paypal" => "Y6EQRE445MYYW",
//             "sizes" => array("Small","Medium","Large","X-Large")
//     );     
// /*
//  * This foreach adds a SKU value to each $product in the $products catalog
//  */
//     foreach ($products as $product_id => $product) {
//         $products[$product_id]["sku"] = $product_id;
//     }

//     return $products;
// }
?>