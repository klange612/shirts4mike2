<html>
<head>
	<title><?php echo $pageTitle; ?></title>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css" type="text/css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,700" type="text/css">
	<link rel="shortcut icon" href="<?php echo BASE_URL; ?>favicon.ico">
</head>
<body>

	<div class="header">

		<div class="wrapper">

			<h1 class="branding-title"><a href="<?php echo BASE_URL; ?>">Shirts 4 Mike</a></h1>

			<ul class="nav">
				<li class="shirts <?php if ($section == "shirts") { echo "on"; } ?>"><a href="<?php echo BASE_URL; ?>shirts/">Shirts</a></li>
				<li class="contact <?php if ($section == "contact") { echo "on"; } ?>"><a href="<?php echo BASE_URL; ?>contact/">Contact</a></li>
				<li class="search <?php if ($section == "search") { echo "on"; } ?>"><a href="<?php echo BASE_URL; ?>search/">Search</a></li>
				<li class="about <?php if ($section == "about") { echo "on"; } ?>"><a href="<?php echo BASE_URL; ?>about/">About</a></li>
				<li class="cart"><a target="paypal" href="https://www.paypal.com/cgi-bin/webscr?cmd=_cart&amp;business=Q6NFNPFRBWR8S&amp;display=1">Shopping Cart</a></li>
		<?php if ($section != "search") { ?>
				<div class="shirts search page">
					<form method="get" action=<?php echo '"' . BASE_URL . 'search/"'?>>
						<input type="text" name="s" value=<?php if (isset($search_term)) { echo htmlspecialchars($search_term); } ?>>
						<input type="submit" value="Go">
					</form>
				</div>
				</div>
		<?php } ?>
			</ul>
		</div>

	</div>

	<div id="content">