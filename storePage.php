<html>
    <head>
    	<script src="https://kit.fontawesome.com/9d4359df6d.js"></script>
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
		<!-- Store body codes -->
		<div class="store-body">
		
		<h1>Top Reviewed Sellers</h1>
		
		<div class="sell-column">
    		<div class="container">
        		<div class="box-view">
        			<div class="sell-info">
        				<h3>Caleb</h3>
        				<p id="sell-price">Price Range: $100+</p>
        				<p>I enjoy pentesting as a hobby</p>
        				<p id="rating">5 <i class="fas fa-star fa-sm"></i> <a>(121)</a></p>
        			</div>
        		</div>
    		</div>
    		
    		<div class="container">
        		<div class="box-view">
        			<div class="sell-info">
        				<h3>Javier</h3>
        				<p id="sell-price">Price Range: $60+</p>
        				<p>Have past job experiences</p>
        				<p id="rating">4.7 <i class="fas fa-star fa-sm"></i> <a>(152)</a></p>
        			</div>
        		</div>
    		</div>
    		
    		<div class="container">
        		<div class="box-view">
        			<div class="sell-info">
        				<h3>Jack</h3>
        				<p id="sell-price">Price Range: $160+</p>
        				<p>Professional Pen-tester</p>
        				<p id="rating">4.4 <i class="fas fa-star fa-sm"></i> <a>(32)</a></p>
        			</div>
        		</div>
    		</div>
    		
    		<div class="container">
        		<div class="box-view">
        			<div class="sell-info">
        				<h3>Kevin</h3>
        				<p id="sell-price">Price Range: $45+</p>
        				<p>I do pentesting as a part time job</p>
        				<p id="rating">4.2 <i class="fas fa-star fa-sm"></i> <a>(37)</a></p>
        			</div>
        		</div>
    		</div>
		</div>
		
		<h1>Lowest Price</h1>
		<div class="box-view">
			<div class="sell-info">
				<h3>Sean </h3>
				<p id="sell-price">Price Range: $10+</p>
				<p>New to renting my services</p> <!-- Brief description -->
				<p id="rating">4.9 <i class="fas fa-star fa-sm"></i> <a>(7)</a></p>
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
    margin-bottom: 3%;
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
    margin: 12px;
    padding: 8px;
    text-decoration:none;
    color: white;
    }
    .webhead-right .nav-but {
    border: 1px solid white;
    border-radius: 3px;
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
    .sell-column{
    display:flex;
    
    }
    .sell-column .container{
    padding-right:20px;
    }
    
    .box-view {
    position: relative;
    width: 260px;
    height: 320px;
    background-color:#fdfdf8;
    overflow: hidden;
    box-shadow: 0 2px 4px 0 rgba(0,0,0,0.4);
    border: 1px solid black;
    }
    .box-view .sell-info {
    position: absolute;
    width: 100%;
    height: 35%;
    background-color:lightgray;
    bottom: 0;
    }
    .box-view .sell-info h3 {
    padding: 3% 6%;
    }
    .box-view .sell-info p {
    padding: 0 6%;
    }
    .box-view #rating{
    font-size:20px;
    }
    .box-view i {
    color:#FFD700;
    }
    .box-view a{
    font-size:16px;
    }
    .box-view .sell-info #sell-price {
    font-size:18px;
    }
    
    
    </style>
</html>