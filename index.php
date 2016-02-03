<?php
require_once "mysql.php";

$res = $mysqli->query("SELECT id,name FROM fbuser WHERE checked = 1 ORDER BY name");
$select = "<select name='fb_id'>";
while ($row = $res->fetch_assoc())
	$select .= "<option value='".$row["id"]."'>".$row["name"]."</option>";
$select .= "</select>";

if(isset($_POST) && isset($_POST["fb_id"])){
	$fb_id = $_POST["fb_id"];

	//Likes del usuario
	$query = "SELECT * FROM fblikes WHERE fb_id LIKE '%$fb_id%' ORDER BY name"; 
	$res = $mysqli->query($query);
	$likes = "<strong>Mis Likes</strong><br>";
	$likes .= "<table>
				<tr>
				<td>category</td>
				<td>name</td>
				<td>created time</td>
				<td>id</td>
				</tr>";
	while ($row = $res->fetch_assoc()) {
		$likes .= "	<tr>
						<td>". $row["category"]."</td>
						<td>". $row["name"]."</td>
						<td>". $row["created_time"]."</td>
						<td>". $row["id"]."</td>
						</tr>";
	}
	$likes .= "</table><br>";
	
	//Friends
	$query = "SELECT fb_friend_id FROM fbfriends WHERE fb_id = '$fb_id'";
	$res = $mysqli->query($query);
	
	$likes .= "<h2><strong>Lista de Likes de mis Amigos</strong></h2>:<br>";
	
	while ($row = $res->fetch_assoc()) {
		$fb_friend_id = $row["fb_friend_id"];
		$res2 = $mysqli->query("SELECT *,fblikes.name as cname FROM fblikes LEFT JOIN fbuser ON (fblikes.fb_id=fbuser.id) WHERE fb_id LIKE '%$fb_friend_id%' ORDER BY fblikes.name");
		
		if ($res2->num_rows == 0)
			continue;

		$first = true;
		while ($row2 = $res2->fetch_assoc()) {
			if($first == true){
				$likes .="<br><strong>".$row2["fb_name"]."</strong><br>";
				//$likes .="<br><br><br>";
				$likes .= "<table>
				<tr>
				<td>category</td>
				<td>name</td>
				<td>created time</td>
				<td>id</td>
				</tr>";
				$first = false;
			}
			$likes .= "	<tr>
						<td>". $row2["category"]."</td>
						<td>". $row2["cname"]."</td>
						<td>". $row2["created_time"]."</td>
						<td>". $row2["id"]."</td>
						</tr>";
		}
		$likes .= "</table>";
	}
}
?>
<html>
	<body>
		<h1>Likes  <?= $fb_name ?></h1>
		<form method="post">
		<label>Usuario:</label>
		<?= $select ?>
		<input type="submit" value="Consultar" />
		<br>
		<label><a href="facebook.php">Registro</a></label>
		</form>
		<br>
		<?php
		if(isset($likes))
			echo $likes; 
		?>
	</body>
</html>