<?php
header('Content-Type: application/json');

// database connection
$user = "Pgma14";
$password = "Geogre333";
$database = "Pgma14";
$db = new mysqli("50.62.209.5:3306", $user, $password, $database);
// if database connection failed, stop here
if ($db->connect_error) die("Failed to connect to MySQL");

extract($_POST);  //Post should contain categories, state, page #, number of elements to display, etc.

$where="";//The WHERE clause  in the mysql query
$failed_categories = array();//Some categories may be invalid

//If there are categories given, set them
if(isset($categories) and $categories){
     include 'categories.php'; //Specifies all of the valid categories in array $all_categories
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
//categories were not specified, so just let where be nothing


//If the state is set, restrict the database query to that state
if(isset($state) and $state){
    include 'state_abbreviations.php'; //All of the state abbreviations
    $state = isset($state2abbreviation[strtoupper($state)]) ? $state2abbreviation[strtoupper($state)] : $state;
    $where .= "State = '$state'";
    $state = $state_abbreviations[$state];
}
else{
    //No state was specified, so allow laws from any state.
    $where .= "TRUE";
}


//Page specifiers
$rpp = isset($rpp) ? intval($rpp) : 20; // if not stated, 20 rows per page
$page = isset($page) ? intval($page) : 1; // if not stated, show page 1


$sort = (isset($sort) and in_array($sort, array('Title','ID','Category','Date','Tags','Link','Demographics','State'))) ? "ORDER BY $sort" : ""; 

// custom query function, returns result as associative array
function query($query) {
        global $db, $rpp, $page;
        $result = $db->query($query);
        if (is_bool($result)) return false;
        if ($result->num_rows == 0) return null;
        while ($row = $result->fetch_assoc()) {
                $row[FileName]="<a href='Admin/Bills?filename=".$row[FileName]."'>".$row[Title]."</a>";
                if($row[ID][0]=="1"){
                        $row[ID]="HB".substr($row[ID],1);
                }
                if($row[ID][0]=="2"){
                        $row[ID]="SB".substr($row[ID],1);
                }
                $rows[] = $row;
        } 
        return $rows;
}
 // read table data
$html = "";
$first = ($page - 1) * $rpp;
$query="SELECT * FROM Laws WHERE $where $sort LIMIT $first, $rpp";
$rows = query($query);

if ($rows) {
                foreach ($rows as $row) {
                        $html .= "<tr>";
                        foreach ($row as $cell) $html .= "<td>$cell</td>";
                        $html .= "</tr>";
                }
                $nolaws = false;
        }
else{
              $nolaws = true;
}
        $next=$page*$rpp;
        echo json_encode(array(
                "html"=>utf8_encode($html),
                "nolaws"=>$nolaws,
                "failedcategories"=>$failed_categories,
                "lastpage" => (bool) query("SELECT * FROM $table WHERE $where $sort LIMIT $next, $rpp")
        ));
?>