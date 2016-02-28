<?php

require_once "utility.php";

session_start();

$first_name = $rest_name = $name = $statement = $path = $html = "";

if (isset($_POST['name']) && !empty($_POST['name'])) {
	$name = $_POST['name'];
	$result = queryMysql('SELECT `Name`, `Statement`, MAX(Score), `ImageLink` FROM `Profile` WHERE `Name`="'.$name.'"');
	$num = $result->num_rows;
	if ($num > 0) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$name = $row['Name'];
		$statement = $row['Statement'];
		$path = $row['ImageLink'];
		$name_array = explode(" ", $name);
		$first_name = $name_array[0];
		$rest_name = "";
		for ($j = 1; $j < sizeof($name_array); $j++) {
			$rest_name .= $name_array[$j] . " ";
		}
		$html .= '<div id="text_wrap"><div id="static_text">' . $first_name . '<br>' . $rest_name . ' is</div>';
		$result3 = queryMysql('SELECT `Statement` FROM `Profile` WHERE `Name`="'.$name.'" ORDER BY `Score` DESC');
		$num3 = $result3->num_rows;
		$row3 = $result3->fetch_array(MYSQLI_ASSOC);
		$html .= '<div class="flow_text" id="flow_text0">' . $row3['Statement'] . '<span id="blinker">|</span></div>';
		for ($k = 1; $k < $num3; $k++) {
			$row3 = $result3->fetch_array(MYSQLI_ASSOC);
			$html .= '<div class="flow_text" id="flow_text'.$k.'" style="display: none;">' . $row3['Statement'] . '<span id="blinker">|</span></div>';
		}
		$html .= '</div>';
	}
} else {
	$result = queryMysql('SELECT * FROM `Profile` GROUP BY `Name`');
	$num = $result->num_rows;
	if ($num > 0) {
		$rand_num = rand(0, $num - 1);
		$result2 = queryMysql('SELECT `Name`, `Statement`, MAX(Score), `ImageLink` FROM `Profile` GROUP BY `Name` LIMIT '.$rand_num.',1');
		$row2 = $result2->fetch_array(MYSQLI_ASSOC);
		$name = $row2['Name'];
		$statement = $row2['Statement'];
		$path = $row2['ImageLink'];
		$name_array = explode(" ", $name);
		$first_name = $name_array[0];
		$rest_name = "";
		for ($j = 1; $j < sizeof($name_array); $j++) {
			$rest_name .= $name_array[$j] . " ";
		}
		$html .= '<div id="text_wrap"><div id="static_text">' . $first_name . '<br>' . $rest_name . ' is</div>';
		$result3 = queryMysql('SELECT `Statement` FROM `Profile` WHERE `Name`="'.$name.'" ORDER BY `Score` DESC');
		$num3 = $result3->num_rows;
		$row3 = $result3->fetch_array(MYSQLI_ASSOC);
		$html .= '<div class="flow_text" id="flow_text0">' . $row3['Statement'] . '<span id="blinker">|</span></div>';
		for ($k = 1; $k < $num3; $k++) {
			$row3 = $result3->fetch_array(MYSQLI_ASSOC);
			$html .= '<div class="flow_text" id="flow_text'.$k.'" style="display: none;">' . $row3['Statement'] . '<span id="blinker">|</span></div>';
		}
		$html .= '</div>';
	}
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>In Three Words</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="src/jquery.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
</head>
<body>
    <form action="form.php" method="post" id="create_new">
        <span>Create new</span>
        <label>Name: </label><input name="name" type="text"></input><br>
        <label>Three words: </label><input name="statement" type="text"></input><br>
        <button type="button" class="write_button" id="close_create_new">close</button>
        <button type="submit" class="write_button">Submit</button>
    </form>
    <div id="main_wrap">
        <img id="main_background" src=<?php echo $path; ?>>
        <?php echo $html; ?>
        <form action="form.php" method="get">
        	<button type="button" name="name" class="write_button" id="write_own">write your own</button>
            <button name="name" value="<?php echo $name ?>" class="write_button">vote</button>
            <button type="button" name="name" class="write_button" id="help_button">help</button>
        </form>
        <input type="hidden" id="id_num_statements" value=<?php echo $num3; ?>>
    </div>
    <div id="help_wrap">
        <div>Three words are all you need.<br><br>
        Describe, in three words, any person in the World you desire.<br><br>
        You will be able to see who else shares the same ideas as you do!</div>
        <button class="write_button" id="help_return">return</button>
    </div>
</body>
</html>