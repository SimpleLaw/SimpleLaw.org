<?php
// database connection
$user = "Pgma14";
$password = "Geogre333";
$database = "Pgma14";
$db = new mysqli("50.62.209.5:3306", $user, $password, $database);
// if database connection failed, stop here
if ($db->connect_error) die("Failed to connect to MySQL");
 
/*  USAGE INFORMATION
 *  show which table, pagination (20 rows per page, page 1)
 *  URL example:
 *  http://localhost/tables/?table=tv&rpp=20&page=1
 */
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
function getTableHTML() {
        global $page, $rpp, $table, $where;
        $html = "";
        $first = ($page - 1) * $rpp;
        $query="SELECT * FROM $table WHERE $where LIMIT $first, $rpp";
        $rows = query($query);
        if ($rows) {
                $html .= "<table id=\"laws\"><thead><tr>";
                // column names
                foreach ($rows[0] as $key => $value){
                        if($key == "FileName"){
                                $html .= "<th>Link</th>";
                        }
                        else{
                                $html .= "<th>$key</th>";
                        }
                }
                $html .= "\n</tr><tbody>";
                // rows
                foreach ($rows as $row) {
                        $html .= "<tr>";
                        foreach ($row as $cell) $html .= "<td>$cell</td>";
                        $html .= "</tr>";
                }
                $cols = count($rows[0]);
                $html .= "<tr><td id='pagination' colspan='$cols'>" . left() . right() . "</td></tr>";
                $html .= "</tbody></table>";
        }
        else{
              echo("<h2>Sorry, we could not find any laws with this description</h2>");
        }
        return $html;
}
 
function left() {
        global $table, $page, $rpp;
        $doc = $_SERVER['PHP_SELF'];
        $prev = $page - 1;
        return ($prev) ? "<a style='float: left' href='$doc?table=$table&page=$prev&rpp=$rpp'>Page $prev</a>" : "";
}
 
function right() {
        global $table, $page, $rpp;
        $doc = $_SERVER['PHP_SELF'];
        $next = $page + 1;
        $first = ($page) * $rpp;
        $r = query("SELECT * FROM $table LIMIT $first, $rpp");
        return count($r) ? "<a style='float: right' href='$doc?table=$table&page=$next&rpp=$rpp'>Page $next</a>" : "";
}
 
?>