<?php
	mysql_connect('50.62.209.5','User','Password') or die ("<html><script language='JavaScript'>alert('Unable to connect to database! Please try again later.'),history.go(-1)</script></html>");
	mysql_select_db('User');
	mysql_query("INSERT INTO Laws VALUES('Test',00000000000,'None','01/01/01','None','None')");
?>
