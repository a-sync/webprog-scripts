<?php
	require('functions.php');

	head('Account lista', 'acclist.php');

	$orderby = 'aid';//order by kihasználása (alap az id, get-el beszerezhetõ a többi)
					//linkel az oszlop tetején a tobbit is sorra venni (asc/desc)

	$query = "SELECT * FROM tam_accounts ORDER BY '$orderby' ASC";
	$result = mysql_query($query);
	$nrows = mysql_num_rows($result);

	if($nrows < 1) { echo 'Nincs egy account sem!'; }

	echo '<table align="center" border="1">';
	echo '<tr><td>ratio</td><td>value</td><td>id</td><td>site_id</td><td>user</td><td>user_id</td><td>password</td><td>email</td><td>email_pass</td><td>ratio stability</td><td>upload</td><td>download</td><td>passkey</td><td>tam_account</td><td>tam_passkey</td><td>tam_status</td><td>comm</td><td>admin_comm</td></tr>';

	for($i = 1; $nrows >= $i; $i++) {
		$row = mysql_fetch_assoc($result);

		$RATIODATA = ratioCalc($row['upload'], $row['download']);

		echo '<tr>';
		echo '<td>'.$RATIODATA['ratio'].'</td>';
		echo '<td>'.$RATIODATA['value'].'</td>';
		echo '<td><a href="manageacc.php?action=modify_acc&aid='.$row['aid'].'">'.$row['aid'].'</a></td>';
		echo '<td>'.$row['site_id'].'</td>';
		echo '<td>'.$row['user'].'</td>';
		echo '<td>'.$row['user_id'].'</td>';
		echo '<td>'.$row['password'].'</td>';
		echo '<td>'.$row['email'].'</td>';
		echo '<td>'.$row['email_pass'].'</td>';
		echo '<td>'.number_format($RATIODATA['datatraffic'], 0, '', '').'</td>';
		echo '<td>'.$row['upload'].'</td>';
		echo '<td>'.$row['download'].'</td>';
		echo '<td>'.$row['passkey'].'</td>';
		echo '<td>'.$row['tam_account'].'</td>';
		echo '<td>'.$row['tam_passkey'].'</td>';
		echo '<td>'.$row['tam_status'].'</td>';
		echo '<td>'.$row['comm'].'</td>';
		echo '<td>'.$row['admin_comm'].'</td>';
		echo '</tr>';
	}

	echo '</table>';

	foot();
?>