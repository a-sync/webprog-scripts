<html>
<head>
<title>üzenőfal</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="identifier-url" content="www.presidance.com" />
 <meta name="author" content="Presidance Company" />
 <meta name="content-language" content="hu" />
 <meta name="organization" content="Presidance Company" />
 <meta name="publisher" content="Presidance Company" />
 <meta name="copyright" content="copyright 2008 by Presidance Company" />
 <meta name="public" content="yes" />
 <meta name="revisit-after" content="7 days" />
 <meta name="robots" content="INDEX,FOLLOW" />
 <meta name="Description" content="" />
 <meta name="KeyWords" content="tánc, experidance, presidance, felnőtt, táncstílus, társastánc, versenytánc, rendezvény, buli, terembérlés, dance company, performance, show, élőzene, előadás, társulat, produkció, színház, néptánc, modern" />
<style type="text/css">
<!--
body,td,th {
	font-size: 14px;
	color: #333333;
}
h1 {
	font-size: 16px;
	color: #000000;
}
h2 {
	font-size: 16px;
	color: #000000;
}
h1,h2,h3,h4,h5,h6 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}


.rank {
  line-height: 0px;
  font-size: 0px;
  background-image: url("nyil.png");
  width: 20px;
  height: 20px;
}
#uzenofal {
  width: 100%;
  height: 100%;
  overflow: auto;
}

#msg textarea {
width: 50%;
height: 80px;
}


#login .username {
margin-left: 4px;
}
#login .pass {
margin-left: 55px;
}
#login .submit {
margin-left: 95px;
}

#reg .username {
margin-left: 4px;
}
#reg .pass {
margin-left: 55px;
}
#reg .re_pass {
margin-left: 30px;
}
#reg .email {
margin-left: 58px;
}
#reg .submit {
margin-left: 95px;
}

#newpass .username {
margin-left: 4px;
}
#newpass .email {
margin-left: 58px;
}
#newpass .submit {
margin-left: 95px;
}

#modpass .pass {
margin-left: 4px;
}
#modpass .re_pass {
margin-left: 28px;
}
#modpass .submit {
margin-left: 58px;
}

.mind {
  margin: 0;
  padding: 5px;
}
.masik {
  background-color: #E9E9E9;
}


.del {
  color: red;
}

.name {
  font-weight: bold;
}

.time {
  font-color: gray;
  font-size: 12px;
}

-->
</style>

<script type="text/javascript">
<!--
function toggle(name, type, n)
{
  if (!type) type = 0;
  if (type == 1) var e = document.getElementsByTagName(name)[n];
  else var e = document.getElementById(name);
  e.style.display = e.style.display == "none" ? "" : "none";
}
//-->
</script>
<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<?php
if($just_confirmed === true) {
  echo '<meta http-equiv="refresh" content="3;url=http://presidance.com/">';
}
?>
</head>
<body bgcolor="#020202" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- ImageReady Slices (webcut_935x422_tartalom_1.psd) -->
<table id="Table_01" width="935" height="422" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="354" rowspan="2" valign="top">
			<img src="../images/webcut_tartalom_megalakulas_01.jpg" width="354" height="422" alt=""></td>
		<td width="581" height="35" background="../images/webcut_tartalom_megalakulas_02.jpg">&nbsp;</td>
  </tr>
	<tr>
		<td height="387" valign="top" bgcolor="#FFFFFF">
<div id="uzenofal">
<?php if($error) { echo '<font color="red">'.$error.'</font><br/>'; } ?>

<?php
  if($_USER) { include 'sender.php'; }
  else { include 'login.php'; }
  ?>

  <br/>

  <?php
  $query = "SELECT * FROM `messages` ORDER BY `sent` DESC LIMIT 0, $_msgnum";
  $messages = mysql_query($query);
  $resultnum = @mysql_num_rows($messages);

  if($resultnum < 1) {
    echo 'Nincsenek üzenetek...';
  }
  else {
    include 'messages.php';
  }
?>
</div>
		</td>
	</tr>
</table>
<!-- End ImageReady Slices -->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-3664861-3");
pageTracker._trackPageview();
</script>
</body>
</html>