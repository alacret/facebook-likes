<?php
require_once("php-sdk/facebook.php");
require_once("mysql.php");

$config = array();
$config['appId'] = '347999435332261';
$config['secret'] = '25ef1ce243b567da3e6b602d9e2601ff';
$facebook = new Facebook($config);

$ret = $facebook->api("/me");
$fb_id = $ret["id"];
$fb_name = $ret["name"];
$fb_email = $ret["email"];
//echo $facebook->getAccessToken()."<br>";
//$facebook->setExtendedAccessToken();
//echo $facebook->getAccessToken()."<br>";
//die();
$access_token = $facebook->getAccessToken();

$result = $mysqli->query("INSERT INTO 
													fbuser (name, email, id, appId, secret, accessToken, checked) 
										VALUES 
													('$fb_name','$fb_email','$fb_id','347999435332261','25ef1ce243b567da3e6b602d9e2601ff','".$access_token."',0)  ");

$ret = $facebook->api("/me?fields=friends.fields(id,name,hometown,email)");
$friends = $ret["friends"]["data"];

echo "Insertando amigos <br>";

foreach ($friends as $friend) {
	$fb_friend_id = $friend["id"];
	$fb_friend_name = $mysqli->real_escape_string($friend["name"]);
	$fb_friend_email = $mysqli->real_escape_string($friend["email"]);
	$q = "INSERT INTO fbfriends(fb_id,fb_friend_id) VALUES ('$fb_id','$fb_friend_id')";
	$r = $mysqli->query($q);
	$q = $mysqli->query("INSERT INTO
											fbuser (name, email, id, appId, secret, accessToken, checked)
								VALUES
											('$fb_friend_name','$fb_friend_email','$fb_friend_id','347999435332261','25ef1ce243b567da3e6b602d9e2601ff','".$access_token."',0)  ");
	/*
	continue;
	echo "Insertando likes de amigos <br>";
	
	$ret = $facebook->api("/$fb_friend_id?fields=id,name,likes.limit(200)");
	$likes = $ret["likes"]["data"];
	
	foreach ($likes as $like) {
		$cat = $mysqli->real_escape_string($like["category"]);
		$name = $mysqli->real_escape_string($like["name"]);
		$ct  = $mysqli->real_escape_string($like["created_time"]);
		$id = $mysqli->real_escape_string($like["id"]);
		echo "insertando like: $name de $fb_friend_name ...";
		$q = "INSERT INTO likes(fb_id,fb_name,category,name,created_time,id)
		VALUES ('$fb_friend_id','$fb_name','$cat','$name','$ct','$id')";
		$r = $mysqli->query($q);
		$result = "success";
				if(!$r)
		$result =  "fail: (" . $mysqli->errno . ") " . $mysqli->error;
		echo "
		<tr>
		<td>".$fb_id."</td>
				<td>".$fb_name."</td>
				<td>".$cat."</td>
				<td>".$name."</td>
				<td>".$ct."</td>
				<td>".$id."</td>
				<th>".$result."</th>
			</tr>";
	}
	*/
}

echo "Insertando likes propios <br>";

$ret = $facebook->api("/me?fields=id,name,likes.limit(200)");
$likes = $ret["likes"]["data"];

?>
<html>
	<body>
		<table>
			<tr>
				<th>Facebook id</th>
				<th>Facebook Name</th>
				<th>Category</th>
				<th>Name</th>
				<th>Created Time</th>
				<th>Id</th>
				<th>Saved?</th>
			</tr>
			<?php
			foreach ($likes as $like) {
				$cat = $mysqli->real_escape_string($like["category"]);
				$name = $mysqli->real_escape_string($like["name"]);
				$ct  = $mysqli->real_escape_string($like["created_time"]);
				$id = $mysqli->real_escape_string($like["id"]);
				$q = "INSERT INTO fblikes(fb_id,fb_name,category,name,created_time,id) 
				VALUES ('$fb_id','$fb_name','$cat','$name','$ct','$id')";
				$r = $mysqli->query($q);
				$result = "success";
				if(!$r)
    				$result =  "fail: (" . $mysqli->errno . ") " . $mysqli->error;
				echo "
			<tr>
				<td>".$fb_id."</td>
				<td>".$fb_name."</td>
				<td>".$cat."</td>
				<td>".$name."</td>
				<td>".$ct."</td>
				<td>".$id."</td>
				<th>".$result."</th>
			</tr>";
			}
			?>
		</table>
	</body>
</html>
<?php 
$result = $mysqli->query("UPDATE 
					fbuser  
								SET 
					checked  = 1
							WHERE
		 			id = '$fb_id'
 ");
echo "<br>";
?>