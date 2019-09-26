<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
<head><title>Time Zone Converter</title>
    <style>
        th {
            text-align: left;
        }

        table, th, td {
            border: 2px solid grey;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.2em;
        }
    </style>
</head>
<body>

<?php
if ($_GET['uName']) {
	$user_host = '192.168.2.13';
	$user_name = 'fvision';
	$user_user = 'webuser';
	$user_passwd = 'insecure_db_pw';
//mysql -h 192.168.2.13 -D fvision -u webuser -p

	$user_pdo_dsn = "mysql:host=$user_host;dbname=$user_name";

	$user_pdo = new PDO($user_pdo_dsn, $user_user, $user_passwd);
	$q = $user_pdo->query("SELECT * FROM users WHERE uname = '" . $_GET['uName'] . "';");
//	echo "<table><tr><th>User Name</th><th>From city</th><th>To city</th></tr>";
	$row = $q->fetch();
//	echo "<tr><td>" . $row["uname"] . "</td><td>" . $row["fromcity"] . "</td><td>" . $row["tocity"] . "</td></tr>";
	$from = $row["fromcity"];
	$to = $row["tocity"];
	echo "Welcome, " . $row["uname"];
}
echo "</table>";
?>
<h1>Time Zone</h1>
<?php
$db_host = '192.168.2.12';
$db_name = 'fvision';
$db_user = 'webuser';
$db_passwd = 'insecure_db_pw';
//mysql -h 192.168.2.12 -D fvision -u webuser -p
$db_pdo_dsn = "mysql:host=$db_host;dbname=$db_name";
$db_pdo = new PDO($db_pdo_dsn, $db_user, $db_passwd);
date_default_timezone_set("Pacific/Auckland");
if ($_GET['from']) {
	$from = $_GET['from'];
	$to = $_GET['to'];
	$hour = $_GET['hour'];
	$min = $_GET['min'];
} else {
	$hour = date("H");
	$min = date("i");
}
?>
<form method="get">
    <input type="hidden" name="uName" value="<?php echo $_GET['uName'] ?>">
    <p>
        <label for="from">From</label>
        <select name="from" id="from">
			<?php
			$qCities = $db_pdo->query("SELECT * FROM zone");
			while ($row = $qCities->fetch()) {
				echo "<option";
				if ($from === $row["city"]) echo ' selected';
				echo ">" . $row["city"] . "</option>";
			}
			?>
        </select>
    </p>
    <p>
        <label for="to">To&nbsp&nbsp&nbsp&nbsp</label>
        <select name="to" id="to">
			<?php
			$qCities = $db_pdo->query("SELECT * FROM zone");
			while ($row = $qCities->fetch()) {
				echo "<option";
				if ($to === $row["city"]) echo ' selected';
				echo ">" . $row["city"] . "</option>";
			}
			?>
        </select>
    </p>
    <p>
        <label for="">Time</label>
        <select name="hour" id="hour">
			<?php
			for ($x = 0; $x < 24; $x++) {
				$curr = "";
				if ($x < 10) $curr .= "0";
				$curr .= $x;
				echo "<option";
				if ($hour === $curr) echo ' selected';
				echo ">" . $curr . "</option>";
			}
			?>
        </select>&nbsp:
        <select name="min" id="min">
			<?php
			for ($x = 0; $x < 60; $x++) {
				$curr = "";
				if ($x < 10) $curr .= "0";
				$curr .= $x;
				echo "<option";
				if ($min === $curr) echo ' selected';
				echo ">" . $curr . "</option>";
			}
			?>
        </select>
    </p>
    <p>
        <button>Enter</button>
    </p>
</form>
<?php
if ($_GET['from']) {
	$toH = $db_pdo->query("SELECT hour FROM zone WHERE city = '$to'")->fetch()[0];
	$fromH = $db_pdo->query("SELECT hour FROM zone WHERE city = '$from'")->fetch()[0];
	$toM = $db_pdo->query("SELECT minute FROM zone WHERE city = '$to'")->fetch()[0];
	$fromM = $db_pdo->query("SELECT minute FROM zone WHERE city = '$from'")->fetch()[0];
	echo "<p>" . $from . " UTC ";
	if ($fromH > 0) echo "+";
	echo $fromH . ":" . $fromM;
	if ($fromM === '0') echo "0";
	echo "</p><p>Time: " . $hour . ":" . $min . "</p>";

	echo "<p>" . $to . " UTC ";
	if ($toH > 0) echo "+";
	echo $toH . ":" . $toM;
	if ($toM === '0') echo "0";
	$to = mktime($toH, $toM, 0);
	$from = mktime($fromH, $fromM, 0);
	$time = mktime($hour, $min, 0);
	echo "<p>Time: " . date('H:i', mktime($toH, $toM, 0) - mktime($fromH, $fromM, 0)
			+ mktime($hour, $min, 0));
	if ($toH - $fromH + $hour < 0) echo " -1 day";
    elseif ($toH - $fromH + $hour >= 24) echo " +1 day";
	echo "</p>";
}
?>
<p>Showing All Time Zones:</p>
<table border="1">
    <tr>
        <th>City</th>
        <th>Hour</th>
        <th>Minute</th>
    </tr>
	<?php
	$qCities = $db_pdo->query("SELECT * FROM zone");
	while ($row = $qCities->fetch()) {
		echo "<tr><td>" . $row["city"] . "</td><td>" . $row["hour"] . "</td><td>" . $row["minute"] . "</td></tr>\n";
	}
	?>
</table>
</body>
</html>
