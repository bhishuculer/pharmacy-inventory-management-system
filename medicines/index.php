<!DOCTYPE html>
<html>
<head>
	<title>Medicine Search</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="container">
		<h1>Search for Medicines</h1>
		<form action="search.php" method="post">
			<label for="medicine_name">Enter Medicine Name:</label>
			<input type="text" id="medicine_name" name="medicine_name" required>
			<input type="submit" value="Search">
		</form>
			<a href="../pharmacy"><input type="submit" value="Back Home"></a>
	</div>
</body>
</html>
