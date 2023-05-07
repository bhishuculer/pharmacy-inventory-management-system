<!DOCTYPE html>
<html>
<head>
	<title>Search Results</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style>
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
			padding: 5px;
		}

		.table-container {
			display: flex;
			justify-content: center;
			align-items: center;
			margin-top: 20px;
		}
	</style>
</head>
<body>
	<h1>Search for Medicines</h1>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		Medicine name: <input type="text" name="medicine_name">
		<input type="submit" name="submit" value="Search">
		<input type="submit" value="Back Home">
	</form>
	<br>

<?php
require 'vector_representation.php';

$con = mysqli_connect("localhost", "root", "", "medicines");


if(mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

if(!empty($_POST['medicine_name'])){

    $medicine_name = mysqli_real_escape_string($con, $_POST['medicine_name']);

    
    $query1 = "SELECT * FROM available_medicines WHERE medicine_name = '$medicine_name'";
    $result1 = mysqli_query($con, $query1);

    if(mysqli_num_rows($result1) > 0){

        $row1 = mysqli_fetch_assoc($result1);
        $brand = $row1['brand'];
        $composition = $row1['composition'];

        echo "<h2>Original search:</h2>";
        echo "<table>";
        echo "<tr><th>Medicine name</th><th>Brand</th><th>Composition</th></tr>";
        echo "<tr><td>".$medicine_name."</td><td>".$brand."</td><td>".$composition."</td></tr>";
        echo "</table>";

        $query2 = "SELECT medicine_name, brand, composition FROM medicine_composition";
        $result2 = mysqli_query($con, $query2);

        $matching_medicines = array();

        // calculate vector representation of selected medicine's composition
        $selected_medicine_vector = get_vector_representation($composition);

        while($row2 = mysqli_fetch_assoc($result2)){

            $current_medicine_vector = get_vector_representation($row2['composition']);

            $similarity = cosine_similarity($selected_medicine_vector, $current_medicine_vector);

            if($similarity >= 0.5){
                $matching_medicines[] = array(
                    'medicine_name' => $row2['medicine_name'],
                    'brand' => $row2['brand'],
                    'composition' => $row2['composition'],
                    'similarity' => $similarity
                );
            }
        }

        // sort matching medicines by cosine similarity score in descending order
        usort($matching_medicines, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });


        if(count($matching_medicines) > 0){
            // display list of medicines with matching composition in a table
            echo "<h2>Search results:</h2>";
            echo "<table>";
            echo "<tr><th>Medicine name</th><th>Brand</th><th>Composition</th></tr>";
            foreach($matching_medicines as $medicine){
                echo "<tr><td>".$medicine['medicine_name']."</td><td>".$medicine['brand']."</td><td>".$medicine['composition']."</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<h2>No matching medicine found</h2>";
        }
    }
} else {
    echo "<h2>Search for a medicine</h2>";
    echo "<form method='post'>";
    echo "<label for='medicine_name'>Enter the medicine name:</label>";
    echo "<input type='text' id='medicine_name' name='medicine_name' required>";
echo "<input type='text' id='medicine_name' name='medicine_name' required>";
echo "</form>";
echo '</div>';
}

mysqli_close($con);
?>
</div>
</div>
<script>
	$(document).ready(function(){
		$('.search-form').submit(function(){
			$('.table-container').show();
		});
	});
</script>

</body>
</html>
