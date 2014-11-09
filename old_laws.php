<?php
extract($_GET);
$table="Laws";
//If there are categories given, set them
if(isset($categories)){
     $failed_categories = array();//Some categories may be invalid
     include 'categories.php'; //Specifies all of the valid categories in array $all_categories
     $where="";
     //Treat categories as a comma separated values object
     foreach(str_getcsv($categories) as $category){          
          //Remove case from category
          $category = strtolower($category);
          if( in_array( $category, $all_categories)){
                $where  .= "FIND_IN_SET( '$category', Category)>0 AND ";
          }
          else{
                 array_push($failed_categories, $category);
          }

     }

}
else{
    //categories were not specified, so just let where be nothing
    $where = "";
}

//If the state is set, restrict the database query to that state
if(isset($state)){
     include 'state_abbreviations.php'; //All of the state abbreviations
    $state = isset($state2abbreviation[strtoupper($state)]) ? $state2abbreviation[strtoupper($state)] : $state;
    $where .= "State = '$state'";
    $state = $state_abbreviations[$state];
}
else{
    //No state was specified, so allow laws from any state.
    $where .= "TRUE";
$state="All Laws";
}


//Page specifiers
$rpp = isset($rpp) ? intval($rpp) : 20; // if not stated, 20 rows per page
$page = isset($page) ? intval($page) : 1; // if not stated, show page 1

include "table.php";
?>
<!DOCTYPE html>
 <html>
  <head>
    <title>Laws for <?php echo $_GET["stateName"];?> | SimpleLaw</title>
		<link rel="stylesheet" href="Simple%20Law%202_files/styles.css">
		<link rel="stylesheet" type="text/css" href="simplelaw.css">
		<link rel="stylesheet" type="text/css" href="simplelawhome.css">
                <link rel="shortcut icon" href="/simplelawico.ico" >
                <style>
                        table {
                                margin: 10px auto
                        }
                        th {
                                background-color: #ccc
                        }
                        tr {
                                background-color: #fff
                        }
                        th, td {
                                padding: 4px 10px
                        }
                        #pagination {
                                overflow: auto
                        }
                        h3{
                                font-size: 50px;
                                text-align:center;
                        }
                        h2{
                                font-size: 30px;
                                text-align:center;
                        }
                </style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="jquery.tablesorter.min.js"></script>
                <script>
$(document).ready(function() {
     $("#laws").tablesorter({ sortlist: [0,0] });
});
                </script>
  </head>
  <body>
		<div id="headerBackground">
		   <?php include('Simple-Law-Header.svg');?>
</div>

   <?php
     if(count($failed_categories) != 0){
          $output = "";
          foreach($failed_categories as $failed_category){
                 $output .= $failed_category . ", ";
          }
          $output = substr($output,0, -2); 
           echo("<h2>We apologize but $output are not valid categories</h2>");
     }
     echo "<h3>".$state."</h3>";
     echo getTableHTML();
   ?>

  </body>
 </html> 