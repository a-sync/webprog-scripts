<?php
	require('functions.php');

	head('Oldal lista', 'sitelist.php');

	$orderby = 'sid';//order by kihasználása (alap az id, get-el beszerezhetõ a többi)
					//linkel az oszlop tetején a tobbit is sorra venni (asc/desc)

	$query = "SELECT * FROM tam_sites ORDER BY '$orderby' ASC";
	$result = mysql_query($query);
	$nrows = mysql_num_rows($result);

	if($nrows < 1) { echo 'Nincs egy torrent oldal sem!'; }

	echo '<table align="center" border="1">';
	echo '<tr><td>id</td><td>name</td><td>domains</td><td>passkey_link</td><td>user_link</td><td>dindata_link</td><td>restrict_calc</td><td>comm</td><td>admin_comm</td>';

	for($i = 1; $nrows >= $i; $i++) {
		$row = mysql_fetch_assoc($result);

		echo '<tr>';
		echo '<td>'.$row['sid'].'</td>';
		echo '<td>'.$row['name'].'</td>';
		echo '<td>'.$row['domains'].'</td>';
		echo '<td>'.$row['passkey_link'].'</td>';
		echo '<td>'.$row['user_link'].'</td>';
		echo '<td>'.$row['dindata_link'].'</td>';
		echo '<td>'.$row['restrict_calc'].'</td>';
		echo '<td>'.$row['comm'].'</td>';
		echo '<td>'.$row['admin_comm'].'</td>';
		echo '</tr>';
	}

	echo '</table>';

	foot();
?>