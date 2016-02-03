<?php
require_once("php-sdk/facebook.php");
require_once("mysql.php");

$config = array();
$config['appId'] = '347999435332261';
$config['secret'] = '25ef1ce243b567da3e6b602d9e2601ff';
$facebook = new Facebook($config);

$res = $mysqli->query("SELECT 
														*	
											FROM 
													fbuser 
											WHERE
														checked = 0
											ORDER BY 
													rand()
										LIMIT 
														1
												");

while ($row = $res->fetch_assoc()) {
	$fb_id = $row["id"];
	$fb_name = $row["name"];
	$accessToken = $row["accessToken"];
	echo $accessToken;
	echo "Usuario: " . $fb_name;
	$facebook->setAccessToken($accessToken);
	echo "<br>buscando likes";
	$ret = $facebook->api("/".$fb_id."?fields=id,name,likes.limit(200)");
	$likes = $ret["likes"]["data"];
	
	foreach ($likes as $like) {
		$cat = $mysqli->real_escape_string($like["category"]);
		$name = $mysqli->real_escape_string($like["name"]);
		$ct  = $mysqli->real_escape_string($like["created_time"]);
		$id = $mysqli->real_escape_string($like["id"]);
		$q = "INSERT INTO fblikes(fb_id,fb_name,category,name,created_time,id)
		VALUES ('$fb_id','$fb_name','$cat','$name','$ct','$id')";
		$r = $mysqli->query($q);
	}
	
	$result = $mysqli->query("UPDATE
														fbuser
											SET
														checked  = 1
											WHERE
														id = '$fb_id'
	");
}
?>