<html>
	<head>
		<title>Film</title>
		<link rel="icon" type="image/png" href="icon.png" />
		<style>
* {margin: auto; padding: 5px; text-align: center;}
body {background-image: url("bg.jpg"); background-repeat: repeat-y; background-attachment: fixed;}
#all {opacity: 0.9; background-color: #FFFFFF;}
table, tr, td, th {border: 1px solid black; border-collapse: collapse;}
.left {text-align: left;}
.form {border: 0px;}
.wide {width: 100%;}
.split {width: 33%;}
.nopad {padding: 0px;}
.dark {background-color: #E0E0E0;}
.darker {background-color: #C0C0C0;}
.title {background-color: #A0A0A0;}
		</style>
	</head>
	<body>
		<table id="all">
			<tr><td><h1>Film</h1></td></tr>
			<tr>
				<td>
		<?php
function film_display(){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	
	//Create connection
	$conn = new mysqli($servername, $username, $password, $database);

	//Check connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$sql = "SELECT * FROM film ORDER BY Last_Seen DESC, Release_Date DESC, Title ASC";
	$result = mysqli_query($conn, $sql);
	echo $sql."<br /><br />";

	if(mysqli_num_rows($result) > 0){
		echo mysqli_num_rows($result)." Results";
		
		echo "<table><tr class='title'><th>ID</th><th>Title</th><th>Release</th><th>Last Seen</th><th>Rating</th></tr>";
		$darker = true;
		while($row = mysqli_fetch_assoc($result)){
			$last_seen = $row["Last_Seen"];
			$last_seen = preg_replace("/(....)-(..)-(..)/", "$3/$2/$1", $last_seen);
			$last_seen = preg_replace("/(00)\/(00)\/(0000)/", "Unknown", $last_seen);
			
			if($darker){
				echo "<tr class='darker'><td>".$row["ID"]."</td><td class='left'>".$row["Title"]."</td><td>".$row["Release_Date"]."</td><td>".$last_seen."</td><td>".$row["Rating"]."</td></tr>";
				$darker = false;
			}else{
				echo "<tr class='dark'><td>".$row["ID"]."</td><td class='left'>".$row["Title"]."</td><td>".$row["Release_Date"]."</td><td>".$last_seen."</td><td>".$row["Rating"]."</td></tr>";
				$darker = true;
			}
		}
		echo "</table>";
	}else{
		echo "0 results";
	}
	
	mysqli_close($conn);
}

function film_add(){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	$id = $_GET["id"];
	$title = $_GET["title"];
	$title = preg_replace("/([^a-zA-Z0-9,-@# 	])/", "", $title);
	$release_date = $_GET["release_date"];
	if(!empty($_GET["last_seen"])){
		$last_seen = $_GET["last_seen"];
	}else{
		$last_seen = "Unknown";
	}
	$rating = $_GET["rating"];
	
	//Create connection
	$conn = new mysqli($servername, $username, $password, $database);

	//Check connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$sql = "INSERT INTO film VALUES ('".$id."','".$title."','".$release_date."','".$last_seen."','".$rating."')";
	mysqli_query($conn, $sql);
	
	mysqli_close($conn);
}

function film_id(){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	
	//Create connection
	$conn = new mysqli($servername, $username, $password, $database);

	//Check connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$sql = "SELECT ID FROM film ORDER BY ID DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$result = mysqli_fetch_assoc($result);
	$new_id = intval($result['ID']) + 1;
	echo $new_id;
	
	mysqli_close($conn);
}

function film_update(){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	$id = $_GET["id"];
	$title = $_GET["title"];
	$title = preg_replace("/([^a-zA-Z0-9,-@# 	])/", "", $title);
	$release_date = $_GET["release_date"];
	$last_seen = $_GET["last_seen"];
	$unknown = $_GET["unknown"];
	if(!empty($_GET["rating"])){
		$rating = $_GET["rating"];
	}
	
	//Create connection
	$conn = new mysqli($servername, $username, $password, $database);

	//Check connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	if(!empty($title)){
		$sql = "UPDATE film SET Title='".$title."' WHERE ID='".$id."'";
		mysqli_query($conn, $sql);
	}
	if(!empty($release_date)){
		$sql = "UPDATE film SET Release_Date='".$release_date."' WHERE ID='".$id."'";
		mysqli_query($conn, $sql);
	}
	if(!empty($last_seen)){
		$sql = "UPDATE film SET Last_Seen='".$last_seen."' WHERE ID='".$id."'";
		mysqli_query($conn, $sql);
	}
	if(!empty($unknown)){
		$sql = "UPDATE film SET Last_Seen='Unknown' WHERE ID='".$id."'";
		mysqli_query($conn, $sql);
	}
	if(!empty($rating)){
		$sql = "UPDATE film SET Rating='".$rating."' WHERE ID='".$id."'";
		mysqli_query($conn, $sql);
	}
	
	mysqli_close($conn);
}

function film_query(){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	$query = $_GET["query"];
	
	//Create connection
	$conn = new mysqli($servername, $username, $password, $database);

	//Check connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	echo $query."<br /><br />";
	
	$result = mysqli_query($conn, $query);
	
	if(mysqli_num_rows($result) > 0){
		echo mysqli_num_rows($result)." Results";
		
		$fieldinfo = mysqli_fetch_fields($result);
		echo "<table><tr class='title'>";
		foreach($fieldinfo as $field){
			$column = $field->name;
			if($column == "Release_Date"){
				$column = "Release";
			}else if($column == "Last_Seen"){
				$column = "Last Seen";
			}
			
			echo "<th>".$column."</th>";
		}
		echo "</tr>";
		
		$darker = true;
		while($row = mysqli_fetch_assoc($result)){
			if($darker){
				echo "<tr class='darker'>";
				foreach($row as $value){
					$value = preg_replace("/(....)-(..)-(..)/", "$3/$2/$1", $value);
					$value = preg_replace("/(00)\/(00)\/(0000)/", "Unknown", $value);
					echo "<td>".$value."</td>";
				}
				echo "</tr>";
				$darker = false;
			}else{
				echo "<tr class='dark'>";
				foreach($row as $value){
					$value = preg_replace("/(....)-(..)-(..)/", "$3/$2/$1", $value);
					$value = preg_replace("/(00)\/(00)\/(0000)/", "Unknown", $value);
					echo "<td>".$value."</td>";
				}
				echo "</tr>";
				$darker = true;
			}
		}
		echo "</table>";
	}else{
		echo "0 results";
	}
	
	mysqli_close($conn);
}

function film_delete(){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	$id = $_GET["id"];
	
	//Create connection
	$conn = new mysqli($servername, $username, $password, $database);

	//Check connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	if(!empty($id)){
		$sql = "DELETE FROM film WHERE ID='".$id."'";
		mysqli_query($conn, $sql);
	}
	
	mysqli_close($conn);
}

function film_search(){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	$id = $_GET["id"];
	$title = $_GET["title"];
	$release_date = $_GET["release_date"];
	$last_seen = $_GET["last_seen"];
	$rating = $_GET["rating"];
	
	//Create connection
	$conn = new mysqli($servername, $username, $password, $database);

	//Check connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	if(!empty($id)){
		$sql = "SELECT * FROM film WHERE ID ".$id;
		if(!empty($title)){
			$sql = $sql." AND Title ".$title;
		}
		if(!empty($release_date)){
			$sql = $sql." AND Release_Date ".$release_date;
		}
		if(!empty($last_seen)){
			$sql = $sql." AND Last_Seen ".$last_seen;
		}
		if(!empty($rating)){
			$sql = $sql." AND Rating ".$rating;
		}
	}else if(!empty($title)){
		$sql = "SELECT * FROM film WHERE Title ".$title;
		if(!empty($release_date)){
			$sql = $sql." AND Release_Date ".$release_date;
		}
		if(!empty($last_seen)){
			$sql = $sql." AND Last_Seen ".$last_seen;
		}
		if(!empty($rating)){
			$sql = $sql." AND Rating ".$rating;
		}
	}else if(!empty($release_date)){
		$sql = "SELECT * FROM film WHERE Release_Date ".$release_date;
		if(!empty($last_seen)){
			$sql = $sql." AND Last_Seen ".$last_seen;
		}
		if(!empty($rating)){
			$sql = $sql." AND Rating ".$rating;
		}
	}else if(!empty($last_seen)){
		$sql = "SELECT * FROM film WHERE Last_Seen ".$last_seen;
		if(!empty($rating)){
			$sql = $sql." AND Rating ".$rating;
		}
	}else if(!empty($rating)){
		$sql = "SELECT * FROM film WHERE Rating ".$rating;
	}
	
	$sql = $sql." ORDER BY Last_Seen DESC, Release_Date DESC, Title ASC";
	
	echo $sql."<br /><br />";
	
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0){
		echo mysqli_num_rows($result)." Results";
		
		echo "<table><tr class='title'><th>ID</th><th>Title</th><th>Release</th><th>Last Seen</th><th>Rating</th></tr>";
		$darker = true;
		while($row = mysqli_fetch_assoc($result)){
			$last_seen = $row["Last_Seen"];
			$last_seen = preg_replace("/(....)-(..)-(..)/", "$3/$2/$1", $last_seen);
			$last_seen = preg_replace("/(00)\/(00)\/(0000)/", "Unknown", $last_seen);
			
			if($darker){
				echo "<tr class='darker'><td>".$row["ID"]."</td><td class='left'>".$row["Title"]."</td><td>".$row["Release_Date"]."</td><td>".$last_seen."</td><td>".$row["Rating"]."</td></tr>";
				$darker = false;
			}else{
				echo "<tr class='dark'><td>".$row["ID"]."</td><td class='left'>".$row["Title"]."</td><td>".$row["Release_Date"]."</td><td>".$last_seen."</td><td>".$row["Rating"]."</td></tr>";
				$darker = true;
			}
		}
		echo "</table>";
	}else{
		echo "0 results";
	}
	
	mysqli_close($conn);
}

if(isset($_GET['submit'])){
	if($_GET['submit'] == 'Add Film'){
		film_add();
		film_display();
	}else if($_GET['submit'] == 'Update Film'){
		film_update();
		film_display();
	}else if($_GET['submit'] == 'Query Film'){
		film_query();
	}else if($_GET['submit'] == 'Delete Film'){
		film_delete();
		film_display();
	}else if($_GET['submit'] == 'Search Film'){
		film_search();
	}
}else{
	film_display();
}
		?>
				</td>
			</tr>
			<tr>
				<td class="wide">
					<table class="form wide nopad">
						<tr class="form wide nopad">
							<td class="split nopad form">
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
									<table class="form wide">
										<tr><td colspan="2"><h2>Add Film</h2></td></tr>
										<tr><td class="left">ID:</td><td><input type="text" name="id" value="<?php film_id(); ?>" readonly required></input></td></tr>
										<tr><td class="left">Title:</td><td><input type="text" name="title" required></input></td></tr>
										<tr><td class="left">Release:</td><td><input type="text" name="release_date" required></input></td></tr>
										<tr><td class="left">Last Seen:</td><td><input type="date" name="last_seen"></input></td></tr>
										<tr><td class="left">Rating:</td><td>
											<select class="wide" name="rating">
												<option value="" selected></option>
												<option value="U">U</option>
												<option value="PG">PG</option>
												<option value="12A">12A</option>
												<option value="12">12</option>
												<option value="15">15</option>
												<option value="18">18</option>
												<option value="Unrated">Unrated</option>
											</select>
										</td></tr>
										<tr><td colspan="2"><input type="submit" value="Add Film" name="submit"></input></td></tr>
									</table>
								</form>
							</td>
							<td class="split nopad form">
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
									<table class="form wide">
										<tr><td colspan="2"><h2>Update Film</h2></td></tr>
										<tr><td class="left">ID:</td><td><input type="text" name="id" required></input></td></tr>
										<tr><td class="left">Title:</td><td><input type="text" name="title"></input></td></tr>
										<tr><td class="left">Release:</td><td><input type="text" name="release_date"></input></td></tr>
										<tr><td class="left">
											Last Seen:</td><td><input type="date" name="last_seen"><br />
											<input type="checkbox" name="unknown"></input>Unknown</input>
										</td></tr>
										<tr><td class="left">Rating:</td><td>
											<select class="wide" name="rating">
												<option value="" selected></option>
												<option value="U">U</option>
												<option value="PG">PG</option>
												<option value="12A">12A</option>
												<option value="12">12</option>
												<option value="15">15</option>
												<option value="18">18</option>
												<option value="Unrated">Unrated</option>
											</select>
										</td></tr>
										<tr><td colspan="2"><input type="submit" value="Update Film" name="submit"></input></td></tr>
									</table>
								</form>
							</td>
							<td class="split nopad form">
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
									<table class="form wide">
										<tr><td colspan="2"><h2>Search Film</h2></td></tr>
										<tr><td class="left">ID:</td><td><input type="text" name="id"></input></td></tr>
										<tr><td class="left">Title:</td><td><input type="text" name="title"></input></td></tr>
										<tr><td class="left">Release:</td><td><input type="text" name="release_date"></input></td></tr>
										<tr><td class="left">Last Seen:</td><td><input type="text" name="last_seen"></input></td></tr>
										<tr><td class="left">Rating:</td><td><input type="text" name="rating"></input></td></tr>
										<tr><td colspan="2"><input type="submit" value="Search Film" name="submit"></input></td></tr>
									</table>
								</form>
							</td>
						</tr>
					</table>
					<table class="wide form"><tr class="wide form"><td class="wide form">
					<table class="wide">
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
							<tr class="form">
								<td class="form">ID:</td><td class="form wide"><input class="wide" type="text" name="id"></input></td>
								<td class="form"><input type="submit" class="wide" value="Delete Film" name="submit"></input></td>
							</tr>
						</form>
					</table>
					</td></tr><tr class="wide form"><td class="wide form">
					<table class="wide">
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
							<tr class="form">
								<td class="form">Query:</td><td class="form wide"><input class="wide" type="text" name="query"></input></td>
								<td class="form"><input type="submit" class="wide" value="Query Film" name="submit"></input></td>
							</tr>
						</form>
					</table>
					</td></tr></table>
					<table class="form"><tr class="form">
						<td class="form">
							<form action="http://localhost/Media-Test/film.php">
								<input type="submit" value="Refresh" />
							</form>
						</td><td class="form">
							<form action="http://localhost/Media-Test/tv.php">
								<input type="submit" value="TV" />
							</form>
						</td>
					</tr></table>
				</td>
			</tr>
		</table>
	</body>
</html>