<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Boomerang Portal Setup</title>
    <link rel="icon" type="image/x-icon" href="assets/images/boomerang_logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<link href="assets/css/style.css" rel="stylesheet"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-top">
	<div class="container-fluid">
		<a class="navbar-brand" href="#"><img  src="assets/images/boomerang_logo.png"/></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
		</button>
	    <div class="collapse navbar-collapse" id="navbarSupportedContent">
		 <ul class="navbar-nav mr-auto">
		  <li class="nav-item active">
			<a class="nav-link" href="#">How it Works</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="#">Money Saving Tips</a>
		  </li>
		   <li class="nav-item">
			<a class="nav-link" href="#">Sell us Your Boxes</a>
		  </li>
		   <li class="nav-item">
			<a class="nav-link" href="#">About Us</a>
		  </li>
		   <li class="nav-item">
			<a class="nav-link" href="#">FAQ</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="#">Contact Us</a>
		  </li>
		 </ul>
		 
		 <ul class="navbar-nav ml-auto">
		  <li class="nav-item">
			<a class="nav-link" href="#">Login</a>
		  </li>
		 </ul>
	  </div>
	</div>
</nav>
	<!-- navigation menu -->
		<nav class="navbar navbar-expand-xl navbar-mega-menu">
			<div class="container-fluid">
			  <a class="navbar-brand" href="product.php?box_type=gaylord">Browse Inventory</a>
			  <button class="navbar-toggler mr-2" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
					</button>
					<div id="navbarNav" class="collapse navbar-collapse">
					<!-- display on xl only -->
					<div class="navbar-nav d-xl-flex">
						<div id="link-1" class="nav-item dropdown-hover nav-link-mega-menu">
							<a class="nav-link dropdown-hover-button" href="product.php?box_type=gaylord&box_subtype=all">Gaylord Totes</a>
							<div class="dropdown-hover-content">
								<div class="row justify-content-center">
									<div class="col-lg-3 text-center">
									<div class="mb-3">
										<img src="assets/images/gaylord-box1.png" class="img-fluid"/>
										<h6><a class="dark-text" href="product.php?box_type=gaylord&box_subtype=gaylord">Gaylord</a></h6>
									</div>
									</div>
									<div class="col-lg-3 text-center">
									<div class="mb-3">
										<img src="assets/images/gaylord-box2.png" class="img-fluid"/>
										<h6><a class="dark-text" href="product.php?box_type=gaylord&box_subtype=gaylordUCB">GaylordUCB</a></h6>
									</div>
									</div>
									<div class="col-lg-3 text-center">
									<div class="mb-3">
										<img src="assets/images/gaylord-box3.png" class="img-fluid"/>
										<h6><a class="dark-text" href="product.php?box_type=gaylord&box_subtype=PresoldGaylord">PresoldGaylord</a></h6>
									</div>
									</div>
									<div class="col-md-12 mega-menu-hr" >
										<hr>
										<p class="text-center"><a class="mega-menu-view-all" href="product.php?box_type=Gaylord&box_subtype=all">View All</a></p>
									</div>
								</div>
							</div>
						  </div>
						  <div id="link-2" class="nav-item dropdown-hover nav-link-mega-menu">
								<a class="nav-link dropdown-hover-button" href="product.php?box_type=Shipping&box_subtype=all">Shipping Boxes</a>
								<div class="dropdown-hover-content">
									<div class="row justify-content-center">
										<div class="col-lg-3 text-center">
										  <div class="mb-3">
											<img src="assets/images/gaylord-box3.png" class="img-fluid"/>
											<h6><a class="dark-text" href="product.php?box_type=Shipping&box_subtype=Box">Box</a></h6>
										  </div>
										</div>
										<div class="col-lg-3 text-center">
										  <div class="mb-3">
											<img src="assets/images/gaylord-box4.png" class="img-fluid"/>
											<h6><a class="dark-text" href="product.php?box_type=Shipping&box_subtype=Boxnonucb">Box NonUCB</a></h6>
										  </div>
										</div>
										<div class="col-lg-3 text-center">
										  <div class="mb-3">
											<img src="assets/images/gaylord-box2.png" class="img-fluid"/>
											<h6><a class="dark-text" href="product.php?box_type=Shipping&box_subtype=Presold">Presold</a></h6>
										  </div>
										</div>
										<div class="col-md-12 mega-menu-hr" >
											<hr>
											<p class="text-center"><a class="mega-menu-view-all" href="product.php?box_type=Shipping&box_subtype=all" >View All</a></p>
										</div>
									</div>
								</div>
							</div>
							<div id="link-3" class="nav-item dropdown-hover nav-link-mega-menu">
								<a class="nav-link dropdown-hover-button" href="product.php?box_type=Pallets&box_subtype=all">Pallets</a>
								<div class="dropdown-hover-content">
									<div class="row justify-content-center">
										<div class="col-lg-3 text-center">
										  <div class="mb-3">
											<img src="assets/images/gaylord-box4.png" class="img-fluid"/>
											<h6><a class="dark-text" href="product.php?box_type=Pallets&box_subtype=PalletsUCB">Pallets UCB</a></h6>
										  </div>
										</div>
										<div class="col-lg-3 text-center">
										  <div class="mb-3">
											<img src="assets/images/gaylord-box3.png" class="img-fluid"/>
											<h6><a class="dark-text" href="product.php?box_type=Pallets&box_subtype=PalletsNonUCB">Pallets NonUCB</a></h6>
										  </div>
										</div>
										<div class="col-lg-3 text-center">
										  <div class="mb-3">
											<img src="assets/images/gaylord-box2.png" class="img-fluid"/>
											<h6><a class="dark-text" href="#">Standard Pallets</a></h6>
										  </div>
										</div>
										<div class="col-md-12 mega-menu-hr" >
											<hr>
											<p class="text-center"><a class="mega-menu-view-all"  href="product.php?box_type=Pallets&box_subtype=all">View All</a></p>
										</div>
									</div>
								</div>
							</div>
						    <div id="link-4" class="nav-item dropdown-hover nav-link-mega-menu">
							<a class="nav-link dropdown-hover-button" href="#" href="product.php?box_type=Supersacks&box_subtype=all">Supersacks</a>
							<div class="dropdown-hover-content">
								<div class="row justify-content-center">
									<div class="col-lg-3 text-center">
									  <div class="mb-3">
										<img src="assets/images/gaylord-box1.png" class="img-fluid"/>
										<h6><a class="dark-text" href="#" href="product.php?box_type=Supersacks&box_subtype=SupersackUCB">Supersack UCB</a></h6>
									  </div>
									</div>
									<div class="col-lg-3 text-center">
									  <div class="mb-3">
										<img src="assets/images/gaylord-box3.png" class="img-fluid"/>
										<h6><a class="dark-text" href="#" href="product.php?box_type=Supersacks&box_subtype=SupersacknonUCB">Supersack NonUCB</a></h6>
									  </div>
									</div>
									<div class="col-lg-3 text-center">
									  <div class="mb-3">
										<img src="assets/images/gaylord-box4.png" class="img-fluid"/>
										<h6><a class="dark-text" href="product.php?box_type=Supersacks&box_subtype=Supersacks">Supersacks</a></h6>
									  </div>
									</div>
									<div class="col-md-12 mega-menu-hr" >
										<hr>
										<p class="text-center"><a class="mega-menu-view-all" href="product.php?box_type=Supersacks&box_subtype=all">View All</a></p>
									</div>
								</div>
							</div>
							</div>
						</div>	
					</div>
			</div>
		</nav>
	</head>
			
