<html>
    <head>
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    	<title>Pentesters for Hire</title>
    </head>

	<body>
		<div class="webhead">
			<a id="left">Hire a Pentester</a>	
    			<input type="text" id="nav-search" placeholder="Search for Pentester">
    			<button id="nav-sea-but" type="submit">Search</button>
    		<div class="webhead-right">
        		<a href="index.php">Home</a>
        		<a href="">Explore</a>
        		<a href="about.php">About</a>
        		<a class="nav-but" href="login.php">Login</a>
			</div>
		</div>
		<div class="store-body">
		
		<h1>Top Reviewed Sellers</h1>
		<div class="top-reviewed">
			<div class="sell-info">
				<h3>Caleb </h3>
				<p id="sell-price">Price Range: $100+</p>
				<p>I enjoy pentesting as a hobby.</p> <!-- Brief description -->
				<p>Availability: Weekdays</p>
			</div>
		</div>
		
		<h1>Lowest Price</h1>
		<div class="top-reviewed">
			<div class="sell-info">
				<h3>Sean </h3>
				<p id="sell-price">Price Range: $10+</p>
				<p>New to renting my services</p> <!-- Brief description -->
				<p>Availability: Weekdays</p>
			</div>
		</div>
		
	</div>
    </body>
    <style>
    body {
    padding: 0;
    margin: 0;
    width: 100%;
    }
    h1 {
    margin-top: 7%;
    }
    h2, h3, h4, p{
    margin:0;
    padding:0;
    }
    /*Css for headers */
    .webhead {
    height: 80px;
    background: #003366;
    font-size: 24px;
    color: white;
    }
    .webhead #left {
    text-align: center;
    padding: 25px;
    width: 15%;
    float:left;
    }
    .webhead #nav-search{
    margin: 22px 0px;
    margin-left: 10%;
    padding: 8px;
    padding-right: 0px;
    font-size: 16px;
    border-radius: 5px;
    outline-width: 0;
    }
    .webhead #nav-sea-but{
    padding: 8px;
    font-size: 16px;
    border-radius: 5px;
    outline-width: 0;
    }
    .webhead-right {
    float: right;
    margin: 15px;
    padding: 10px;
    }
    .webhead-right a {
    margin: 10px;
    padding: 10px;
    text-decoration:none;
    color: white;
    }
    .webhead-right a:hover{
    color: #DCDCDC;
    }
    /*End of Headers */
    
    /*Start of body */
    .store-body {
    width: 80%;
    margin: auto;
    height: 1200px;
    }
    .top-reviewed {
    position: relative;
    width: 260px;
    height: 320px;
    background-color:#fdfdf8;
    overflow: hidden;
    box-shadow: 0 2px 4px 0 rgba(0,0,0,0.4);
    border: 1px solid black;
    }
    .top-reviewed .sell-info {
    position: absolute;
    width: 100%;
    height: 35%;
    background-color:lightgray;
    bottom: 0;
    }
    .top-reviewed .sell-info h3 {
    padding: 3% 6%;
    }
    .top-reviewed .sell-info p {
    padding: 0 6%;
    }
    .top-reviewed .sell-info #sell-price {
    font-size:18px;
    }
    
    </style>
</html>