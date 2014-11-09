<?php
	$responseText=file_get_contents("http://api.legiscan.com/?key=9635a55e3fb637fb7ce2dc3810fa2833&op=getMasterList&state=CT");
$parsed=explode(",",explode(":",$responseText,9)[7])[0];
echo(file_get_contents("http://api.legiscan.com/?key=9635a55e3fb637fb7ce2dc3810fa2833&op=getBill&id=".$parsed));
?>