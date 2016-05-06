<html>
	<head>
		<title>TV</title>
		<link rel="icon" type="image/png" href="icon.png" />
		<style>
* {margin: auto; padding: 5px; text-align: center;}
body {background-image: url("bg.jpg"); background-repeat: repeat-y; background-attachment: fixed;}
#all {opacity: 0.9; background-color: #FFFFFF;}
table, tr, td, th {border: 1px solid black; border-collapse: collapse;}
.left {text-align: left;}
.form {border: 0px;}
.episode {width: 4em;}
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
			<tr><td><h1>TV</h1></td></tr>
			<tr>
				<td>
		<?php
function tv_display(){
	//The database access details
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	
	//Create the connection to the database
	$conn = new mysqli($servername, $username, $password, $database);

	//Check the connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	//Query the database for all records and write the query on the webpage
	$sql = "SELECT * FROM tv ORDER BY CASE Complete WHEN 'Ongoing' THEN 1 WHEN 'No' THEN 2 WHEN 'Yes' THEN 3 ELSE 4 END, Title ASC";
	$result = mysqli_query($conn, $sql);
	echo "SELECT * FROM tv <br />ORDER BY CASE Complete WHEN 'Ongoing' THEN 1 WHEN 'No' THEN 2 WHEN 'Yes' THEN 3 ELSE 4 END, Title ASC<br /><br />";
	
	//If any records were returned display them
	if(mysqli_num_rows($result) > 0){
		//Write how many results there were for the query
		echo mysqli_num_rows($result)." Results";
		
		//Create a table to display the data in
		echo "<table><tr class='title'><th>ID</th><th>Title</th><th>Episode</th><th>Complete</th><th>Genre</th></tr>";
		//This is used to colour the rows alternating colours
		$darker = true;
		//While there are still records to be put into the table
		while($row = mysqli_fetch_assoc($result)){
			//If the row should be dark or not
			if($darker){
				//Add another row to the table with the data for that record in
				echo "<tr class='darker'><td>".$row["ID"]."</td><td class='left'>".$row["Title"]."</td><td>".$row["Episode"]."</td><td>".$row["Complete"]."</td><td>".$row["Genre"]."</td></tr>";
				$darker = false;
			}else{
				//Add another row to the table with the data for that record in
				echo "<tr class='dark'><td>".$row["ID"]."</td><td class='left'>".$row["Title"]."</td><td>".$row["Episode"]."</td><td>".$row["Complete"]."</td><td>".$row["Genre"]."</td></tr>";
				$darker = true;
			}
		}
		echo "</table>";
	}else{
		echo "0 results";
	}
	
	//Close the connection to the database
	mysqli_close($conn);
}

function tv_add(){
	//The database access details
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	
	//Retrieve information about the record to add from the url
	$id = $_GET["id"];
	$title = $_GET["title"];
	$title = preg_replace("/([^a-zA-Z0-9,-@# 	])/", "", $title);
	$episodeS = $_GET["episodeS"];
	$episodeE = $_GET["episodeE"];
	//Put the series and episode numbers together
	$episode = "S".$episodeS."E".$episodeE;
	$complete = $_GET["complete"];
	$genre = $_GET["genre"];
	
	//Create the connection to the database
	$conn = new mysqli($servername, $username, $password, $database);

	//Check the connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	//Turn the genre array into a string
	$genreCompound = "";
	foreach($genre as $genreSelected){
		if($genreCompound == ""){
			$genreCompound = $genreSelected;
		}else{
			$genreCompound = $genreCompound.",".$genreSelected;
		}
	}
	
	//Insert the new record into the database table
	$sql = "INSERT INTO tv VALUES ('".$id."','".$title."','".$episode."','".$complete."','".$genreCompound."')";
	mysqli_query($conn, $sql);
	
	//Close the connection
	mysqli_close($conn);
}

function tv_id(){
	//The database access details
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	
	//Create a connection to the database
	$conn = new mysqli($servername, $username, $password, $database);

	//Check the connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	//Generate a new unique ID
	$sql = "SELECT ID FROM tv ORDER BY ID DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$result = mysqli_fetch_assoc($result);
	$new_id = intval($result['ID']) + 1;
	echo $new_id;
	
	//Close the database
	mysqli_close($conn);
}

function tv_update(){
	//The database access details
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	
	//Retrieve information about the record to update from the url
	//The ID of the record to change
	$id = $_GET["id"];
	//What data should be changed
	$title = $_GET["title"];
	$title = preg_replace("/([^a-zA-Z0-9,-@# 	])/", "", $title);
	if(!empty($_GET["episodeS"])){
		if(!empty($_GET["episodeE"])){
			$episodeS = $_GET["episodeS"];
			$episodeE = $_GET["episodeE"];
			$episode = "S".$episodeS."E".$episodeE;
		}
	}
	if(!empty($_GET["complete"])){
		$complete = $_GET["complete"];
	}
	if(!empty($_GET["genre"])){
		$genre = $_GET["genre"];
	}
	
	//Create the connection to the database
	$conn = new mysqli($servername, $username, $password, $database);

	//Check the connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	//If a field was not left blank by the user update the record in the database with the new data
	if(!empty($title)){
		$sql = "UPDATE tv SET Title='".$title."' WHERE ID='".$id."'";
		mysqli_query($conn, $sql);
	}
	if(!empty($episode)){
		$sql = "UPDATE tv SET Episode='".$episode."' WHERE ID='".$id."'";
		mysqli_query($conn, $sql);
	}
	if(!empty($complete)){
		$sql = "UPDATE tv SET Complete='".$complete."' WHERE ID='".$id."'";
		mysqli_query($conn, $sql);
	}
	if(!empty($genre) && !empty($genre[0])){
		$genreCompound = "";
		foreach($genre as $genreSelected){
			if($genreCompound == ""){
				$genreCompound = $genreSelected;
			}else{
				$genreCompound = $genreCompound.",".$genreSelected;
			}
		}
		$sql = "UPDATE tv SET Genre='".$genreCompound."' WHERE ID='".$id."'";
		mysqli_query($conn, $sql);
	}
	
	//Close the connection
	mysqli_close($conn);
}

function tv_query(){
	//The database access details
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	$query = $_GET["query"];
	
	//Create a connection to the database
	$conn = new mysqli($servername, $username, $password, $database);

	//Check the connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	//Write the query to the top of the webpage
	echo $query."<br /><br />";
	
	$result = mysqli_query($conn, $query);
	
	//If the query returned any results
	if(mysqli_num_rows($result) > 0){
		//Print how many results the query returned
		echo mysqli_num_rows($result)." Results";
		
		//Create a table with the headings of the fields in the returned data
		$fieldinfo = mysqli_fetch_fields($result);
		echo "<table><tr class='title'>";
		foreach($fieldinfo as $field){
			echo "<th>".$field->name."</th>";
		}
		echo "</tr>";
		
		$darker = true;
		//For each record returned, add it as a new row to the table
		while($row = mysqli_fetch_assoc($result)){
			if($darker){
				echo "<tr class='darker'>";
				foreach($row as $value){
					echo "<td>".$value."</td>";
				}
				echo "</tr>";
				$darker = false;
			}else{
				echo "<tr class='dark'>";
				foreach($row as $value){
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
	
	//Close the connection
	mysqli_close($conn);
}

function tv_delete(){
	//The database access details
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	
	//The ID of the record to delete from the database
	$id = $_GET["id"];
	
	//Create a connection to the database
	$conn = new mysqli($servername, $username, $password, $database);

	//Check the connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	if(!empty($id)){
		//Delete the record with the given ID from the table
		$sql = "DELETE FROM tv WHERE ID='".$id."'";
		mysqli_query($conn, $sql);
	}
	
	//Close the connection
	mysqli_close($conn);
}

function tv_search(){
	//The database access details
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "media-example";
	
	//Retrieve information about what to search for from the url
	$id = $_GET["id"];
	$title = $_GET["title"];
	$episode = $_GET["episode"];
	$complete = $_GET["complete"];
	if(!empty($_GET["genre"])){
		$genre = $_GET["genre"];
	}
	
	//Create a connection to the database
	$conn = new mysqli($servername, $username, $password, $database);

	//Check the connection
	if(!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
	
	//Set the base query to finding everything
	$sql = "SELECT * FROM tv";
	
	//If ID information was given
	if(!empty($id)){
		//Add a search for the given ID information to the base query
		$sql = "SELECT * FROM tv WHERE ID ".$id;
		//If any other information was given also add that to the query
		if(!empty($title)){
			$sql = $sql." AND Title ".$title;
		}
		if(!empty($episode)){
			$sql = $sql." AND Episode ".$episode;
		}
		if(!empty($complete)){
			$sql = $sql." AND Complete ".$complete;
		}
		if(!empty($genre) && !empty($genre[0])){
			foreach($genre as $genreSelected){
				$sql = $sql." AND Genre LIKE '%".$genreSelected."%'";
			}
		}
	//If title information was given
	}else if(!empty($title)){
		//Add a search for the given title information to the base query
		$sql = "SELECT * FROM tv WHERE Title ".$title;
		//If any other information was given also add that to the query
		if(!empty($episode)){
			$sql = $sql." AND Episode ".$episode;
		}
		if(!empty($complete)){
			$sql = $sql." AND Complete ".$complete;
		}
		if(!empty($genre) && !empty($genre[0])){
			foreach($genre as $genreSelected){
				$sql = $sql." AND Genre LIKE '%".$genreSelected."%'";
			}
		}
	//If episode information was given
	}else if(!empty($episode)){
		//Add a search for the given episode information to the base query
		$sql = "SELECT * FROM tv WHERE Episode ".$episode;
		//If any other information was given also add that to the query
		if(!empty($complete)){
			$sql = $sql." AND Complete ".$complete;
		}
		if(!empty($genre) && !empty($genre[0])){
			foreach($genre as $genreSelected){
				$sql = $sql." AND Genre LIKE '%".$genreSelected."%'";
			}
		}
	//If complete information was given
	}else if(!empty($complete)){
		//Add a search for the given complete information to the base query
		$sql = "SELECT * FROM tv WHERE Complete ".$complete;
		//If any other information was given also add that to the query
		if(!empty($genre) && !empty($genre[0])){
			foreach($genre as $genreSelected){
				$sql = $sql." AND Genre LIKE '%".$genreSelected."%'";
			}
		}
	//If genre information was given
	}else if(!empty($genre) && !empty($genre[0])){
		//Add a search for the given genre information to the base query
		$sql = "SELECT * FROM tv WHERE";
		$runs = 0;
		foreach($genre as $genreSelected){
			if($runs == 0){
				$sql = $sql." Genre LIKE '%".$genreSelected."%'";
				$runs += 1;
			}else{
				$sql = $sql." AND Genre LIKE '%".$genreSelected."%'";
			}
		}
	}
	
	//Print the query to the webpage
	echo $sql." <br />"."ORDER BY CASE Complete WHEN 'Ongoing' THEN 1 WHEN 'No' THEN 2 WHEN 'Yes' THEN 3 ELSE 4 END, Title ASC"."<br /><br />";
	
	//Add ordering information to the end of the search query
	$sql = $sql." ORDER BY CASE Complete WHEN 'Ongoing' THEN 1 WHEN 'No' THEN 2 WHEN 'Yes' THEN 3 ELSE 4 END, Title ASC";
	
	//Query the database
	$result = mysqli_query($conn, $sql);

	//If any records were returned
	if(mysqli_num_rows($result) > 0){
		//Print how many records matched the search
		echo mysqli_num_rows($result)." Results";
		
		//Create a table to show the records in
		echo "<table><tr class='title'><th>ID</th><th>Title</th><th>Episode</th><th>Complete</th><th>Genre</th></tr>";
		$darker = true;
		//Add each returned record to the table as a new row
		while($row = mysqli_fetch_assoc($result)){
			if($darker){
				echo "<tr class='darker'><td>".$row["ID"]."</td><td class='left'>".$row["Title"]."</td><td>".$row["Episode"]."</td><td>".$row["Complete"]."</td><td>".$row["Genre"]."</td></tr>";
				$darker = false;
			}else{
				echo "<tr class='dark'><td>".$row["ID"]."</td><td class='left'>".$row["Title"]."</td><td>".$row["Episode"]."</td><td>".$row["Complete"]."</td><td>".$row["Genre"]."</td></tr>";
				$darker = true;
			}
		}
		echo "</table>";
	}else{
		echo "0 results";
	}
	
	//Close the connection
	mysqli_close($conn);
}

//If the page is meant to do something when it loads do that, else just display the database table
if(isset($_GET['submit'])){
	//If the user wants to add a record to the database table
	if($_GET['submit'] == 'Add TV'){
		//Add a record to the database
		tv_add();
		//Display the updated database table
		tv_display();
	//If the user wants to update a record
	}else if($_GET['submit'] == 'Update TV'){
		//Update the database
		tv_update();
		//Display the updated database table
		tv_display();
	//If the user wants to run a custom query on the database
	}else if($_GET['submit'] == 'Query TV'){
		//Run the query
		tv_query();
	//If the user wants to delete a record
	}else if($_GET['submit'] == 'Delete TV'){
		//Delete the right record
		tv_delete();
		//Display the updated database table
		tv_display();
	//If the user wants to search the database
	}else if($_GET['submit'] == 'Search TV'){
		//Search the database table
		tv_search();
	}
}else{
	tv_display();
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
										<!-- Create a form for adding to the database -->
										<tr><td colspan="2"><h2>Add TV</h2></td></tr>
										<!-- The ID value is readonly and set automatically to the next available unique ID, therefore a duplicate cannot be accidentally made -->
										<tr><td class="left">ID:</td><td><input type="text" name="id" value="<?php tv_id(); ?>" readonly required></input></td></tr>
										<tr><td class="left">Title:</td><td><input type="text" name="title" required></input></td></tr>
										<tr><td class="left">Episode:</td><td>S:<input class="episode" type="number" name="episodeS"> E:<input class="episode" type="number" name="episodeE"></input></td></tr>
										<!-- Give the user the a drop down list to select the complete value from -->
										<tr><td class="left">Complete:</td><td>
											<select class="wide" name="complete" required>
												<option value="" selected></option>
												<option value="Yes">Yes</option>
												<option value="No">No</option>
												<option value="Ongoing">Ongoing</option>
												<option value="Will Not">Will Not</option>
											</select>
										</td></tr>
										<!-- Give the user a drop down list to select the genre(s) from. Multiple can be selected at once by holding down control -->
										<tr><td class="left">Genre:</td><td>
											<select class="wide" name="genre[]" multiple required>
												<option value="" selected></option>
												<option value="Animated">Animated</option>
												<option value="Anime">Anime</option>
												<option value="Comedy">Comedy</option>
												<option value="Dark">Dark</option>
												<option value="Fantasy">Fantasy</option>
												<option value="Investigation">Investigation</option>
												<option value="Real Life">Real Life</option>
												<option value="Sci-Fi">Sci-Fi</option>
											</select>
										</td></tr>
										<tr><td colspan="2"><input type="submit" value="Add TV" name="submit"></input></td></tr>
									</table>
								</form>
							</td>
							<td class="split nopad form">
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
									<table class="form wide">
										<!-- Create a form for updating the database -->
										<tr><td colspan="2"><h2>Update TV</h2></td></tr>
										<!-- The only required field is the ID of the record to update -->
										<tr><td class="left">ID:</td><td><input type="text" name="id" required></input></td></tr>
										<tr><td class="left">Title:</td><td><input type="text" name="title"></input></td></tr>
										<tr><td class="left">Episode:</td><td>S:<input class="episode" type="number" name="episodeS"> E:<input class="episode" type="number" name="episodeE"></input></td></tr>
										<tr><td class="left">Complete:</td><td>
											<select class="wide" name="complete">
												<option value="" selected></option>
												<option value="Yes">Yes</option>
												<option value="No">No</option>
												<option value="Ongoing">Ongoing</option>
												<option value="Will Not">Will Not</option>
											</select>
										</td></tr>
										<tr><td class="left">Genre:</td><td>
											<select class="wide" name="genre[]" multiple>
												<option value="" selected></option>
												<option value="Animated">Animated</option>
												<option value="Anime">Anime</option>
												<option value="Comedy">Comedy</option>
												<option value="Dark">Dark</option>
												<option value="Fantasy">Fantasy</option>
												<option value="Investigation">Investigation</option>
												<option value="Real Life">Real Life</option>
												<option value="Sci-Fi">Sci-Fi</option>
											</select>
										</td></tr>
										<tr><td colspan="2"><input type="submit" value="Update TV" name="submit"></input></td></tr>
									</table>
								</form>
							</td>
							<td class="split nopad form">
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
									<table class="form wide">
										<!-- Create a form for searching the database. No fields are required -->
										<tr><td colspan="2"><h2>Search TV</h2></td></tr>
										<tr><td class="left">ID:</td><td><input type="text" name="id"></input></td></tr>
										<tr><td class="left">Title:</td><td><input type="text" name="title"></input></td></tr>
										<tr><td class="left">Episode:</td><td><input type="text" name="episode"></input></td></tr>
										<tr><td class="left">Complete:</td><td><input type="text" name="complete"></input></td></tr>
										<tr><td class="left">Genre:</td><td>
											<select class="wide" name="genre[]" multiple>
												<option value="" selected></option>
												<option value="Animated">Animated</option>
												<option value="Anime">Anime</option>
												<option value="Comedy">Comedy</option>
												<option value="Dark">Dark</option>
												<option value="Fantasy">Fantasy</option>
												<option value="Investigation">Investigation</option>
												<option value="Real Life">Real Life</option>
												<option value="Sci-Fi">Sci-Fi</option>
											</select>
										</td></tr>
										<tr><td colspan="2"><input type="submit" value="Search TV" name="submit"></input></td></tr>
									</table>
								</form>
							</td>
						</tr>
					</table>
					<table class="wide form"><tr class="wide form"><td class="wide form">
					<table class="wide">
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
							<tr class="form">
								<!-- Create a form for deleting a record from the database. Only the ID of the record to remove is needed -->
								<td class="form">ID:</td><td class="form wide"><input class="wide" type="text" name="id"></input></td>
								<td class="form"><input type="submit" class="wide" value="Delete TV" name="submit"></input></td>
							</tr>
						</form>
					</table>
					</td></tr><tr class="wide form"><td class="wide form">
					<table class="wide">
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
							<tr class="form">
								<!-- Create a form for giving a custom query -->
								<td class="form">Query:</td><td class="form wide"><input class="wide" type="text" name="query"></input></td>
								<td class="form"><input type="submit" class="wide" value="Query TV" name="submit"></input></td>
							</tr>
						</form>
					</table>
					</td></tr></table>
					<table class="form"><tr class="form">
						<td class="form">
							<!-- Add a refresh button to the bottom of the page. This removes all the information in the URL -->
							<form action="http://localhost/Media-Test/tv.php">
								<input type="submit" value="Refresh" />
							</form>
						</td><td class="form">
							<!-- Add a button to go directly to the film database page -->
							<form action="http://localhost/Media-Test/film.php">
								<input type="submit" value="Film" />
							</form>
						</td>
					</tr></table>
				</td>
			</tr>
		</table>
	</body>
</html>