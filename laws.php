<?php 
            //Initialize the php variables
            include "state_abbreviations.php"; 
            $stateName=isset($_GET["state"])?$_GET["state"]:"All States";
?>
<!DOCTYPE html>
 <html>
  <head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width">
    <title>Laws for <?php
                              echo (isset($state2abbreviation[strtoupper($stateName)])?$state2abbreviation[strtoupper($stateName)]:$stateName);
                  ?> | SimpleLaw</title>
		<link rel="stylesheet" type="text/css" href="simplelaw.css">
                <link rel="shortcut icon" href="/simplelawico.ico" >
		<link rel="stylesheet" type="text/css" href="simplelawhome.css">
		<link rel="stylesheet" type="text/css" href="catagoriesList.css">
<style>
.errormessage{
text-align:center;
font-size:2em;
font-family:Elephant-Regular;
}
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="laws.js"></script>

</head>
<body onload = "setup(<?php 
extract($_GET);
echo isset($page)?intval($page):1;
echo isset($state) ? ", '$state'":", ''";
echo isset($categories) ? ", '$categories'":", ''";
echo isset($sort) ? ", '$sort'":", ''";
?>)">

		<div id="headerBackground" style="margin-top:-40px;">
		 		<?php include('Simple-Law-Header.svg');?>
		</div>

                 <?php 
                              echo "<h3 style='font-size:3em;text-align:center;'>".(isset($state_abbreviations[$stateName])?$state_abbreviations[$stateName]:$stateName)."</h3>";                              
                  ?>
               <div id = "mainContent">
		<table id="laws">
				   <thead>
		   				   <tr><th>Title</th><th>ID</th><th>Category</th><th>Date</th><th>Tags</th><th>Link</th><th>Demographics</th><th>State</th></tr>
				   </thead>
				   <tbody id="lawsBody">
				   </tbody>
		</table>
<br>
		<div id="footer" style="margin: 10px auto;">
                                   <button id='previous' onclick='gotopreviouspage()'>Previous</button>
				   <span id="goto" style="width:13%;margin-left:35%;">
				   		   <input type="text" size="3" id="newPageNumber">
		   				   <button id="none" onclick="gotopage()">Go</button>
				   </span>
                                    <button id='next' onclick="gotonextpage()">Next</button>
		</div>
                </div>
 </body>
 </html> 