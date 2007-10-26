<?php
	require('functions.php');

	head('Felhasználó lista', 'userlist.php');


	$orderby = 'uid';//order by kihasználása (alap az id, get-el beszerezhetõ a többi)
					//linkel az oszlop tetején a tobbit is sorra venni (asc/desc)

	$query = "SELECT * FROM tam_users ORDER BY '$orderby' ASC";
	$result = mysql_query($query);
	$nrows = mysql_num_rows($result);

	if($nrows < 1) { echo 'Nincs egy felhasználó sem!'; }

	echo '<table align="center" border="1">';
	echo '<tr><td>id</td><td>tam_user</td><td>tam_pass</td><td>tam_email</td><td>tam_accounts</td><td>tam_passkeys</td><td>tam_class</td><td>notif</td><td>verif</td><td>reg_time</td><td>last_ip</td><td>last_login</td><td>admin_comm</td></tr>';

	for($i = 1; $nrows >= $i; $i++) {
		$row = mysql_fetch_assoc($result);

		echo '<tr>';
		echo '<td>'.$row['uid'].'</td>';
		echo '<td>'.$row['tam_user'].'</td>';
		echo '<td>'.$row['tam_pass'].'</td>';
		echo '<td>'.$row['tam_email'].'</td>';
		echo '<td>'.$row['tam_accounts'].'</td>';
		echo '<td>'.$row['tam_passkeys'].'</td>';
		echo '<td>'.$row['tam_class'].'</td>';
		echo '<td>'.$row['notif'].'</td>';
		echo '<td>'.$row['verif'].'</td>';
		echo '<td>'.$row['reg_time'].'</td>';
		echo '<td>'.$row['last_ip'].'</td>';
		echo '<td>'.$row['last_login'].'</td>';
		echo '<td>'.$row['admin_comm'].'</td>';
		echo '</tr>';
	}

	echo '</table>';

	foot();
?>