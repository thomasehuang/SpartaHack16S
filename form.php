<?php

require_once "utility.php";

session_start();

$html = $img = "";

if (isset($_GET['name']) && !empty($_GET['name'])) {
    $name = $_GET['name'];
} else {
    $name = "Donald Trump";
}

if (isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['statement']) && !empty($_POST['statement'])) {
    $name = $_POST['name'];
    $statement = $_POST['statement'];
    $result = queryMysql('SELECT * FROM `Profile` WHERE `Name`="'.$name.'"');
    $num = $result->num_rows;
    if ($num == 0) {
        queryMysql('INSERT INTO `Profile` VALUES (null, "'.$name.'", "'.$statement.'", 0, "", CURDATE(), CURTIME())');
    }
}

$img_result = queryMysql('SELECT * FROM `Profile` WHERE `Name`="'.$name.'"');
$img_num = $img_result->num_rows;
if ($img_num > 0) {
    $img_row = $img_result->fetch_array(MYSQLI_ASSOC);
    $img = $img_row['ImageLink'];
}

if (isset($_GET['set_image']) && $_GET['set_image'] == 1 && isset($_GET['image']) && !empty($_GET['image'])) {
    $img = $_GET['image'];
    queryMysql('UPDATE `Profile` SET `ImageLink`="'.$img.'" WHERE `Name`="'.$name.'"');
}

if (isset($_GET['statement']) && !empty($_GET['statement'])) {
    $statement = $_GET['statement'];
    $statement_array = explode(" ", $statement);
    if (sizeof($statement_array) == 3) {
        $result = queryMysql('SELECT * FROM `Profile` WHERE `Name`="'.$name.'" AND `Statement`="'.$statement.'"');
        $num = $result->num_rows;
        if ($num == 0) {
            queryMysql('INSERT INTO `Profile` VALUES (null, "'.$name.'", "'.$statement.'", 0, "'.$img.'", CURDATE(), CURTIME())');
        }
    }
}

if (isset($_GET['sort']) && !empty($_GET['sort'])) {
    $_SESSION['sort'] = $_GET['sort'];
    $sort = $_GET['sort'];
} else if (isset($_SESSION['sort'])) {
    $sort = $_SESSION["sort"];
} else {
    $_SESSION['sort'] = "new";
    $sort = "new";
}

if ($sort == "new") {
    $result = queryMysql('SELECT * FROM `Profile` WHERE `Name`="'.$name.'" ORDER BY `TableDate`, `TableTime` DESC');
} else {
    $result = queryMysql('SELECT * FROM `Profile` WHERE `Name`="'.$name.'" ORDER BY `Score` DESC');
}
$num = $result->num_rows;
if ($num > 0) {
    for ($j = 0; $j < $num; $j++) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $id = $row['ID'];
        $img = $row['ImageLink'];
        $name = $row['Name'];
        $statement = $row['Statement'];
        $score = $row['Score'];
        $html .= '<div class="display_wrap"><div class="display_text">';
        $html .= $statement;
        if (isset($_SESSION[strval($id)."f"]) && $_SESSION[strval($id)."f"]) {
            $html .= '</div><div class="vote_wrap" value='.$id.'><div class="vote_up" style="background-color: green;" value="'.$name.'">&#128077;</div><div class="vote_down" value="'.$name.'">&#128078;</div></div>';
        } else if (isset($_SESSION[strval($id)."f"]) && !$_SESSION[strval($id)."f"]) {
            $html .= '</div><div class="vote_wrap" value='.$id.'><div class="vote_up" value="'.$name.'">&#128077;</div><div class="vote_down" style="background-color: red;" value="'.$name.'">&#128078;</div></div>';
        } else {
            $html .= '</div><div class="vote_wrap" value='.$id.'><div class="vote_up" value="'.$name.'">&#128077;</div><div class="vote_down" value="'.$name.'">&#128078;</div></div>';
        }
        if ($score > 0) {
            $html .= '<div class="vote_score green_score">'.$score.'</div></div>';
        } else if ($score < 0) {
            $html .= '<div class="vote_score red_score">'.$score.'</div></div>';
        } else {
            $html .= '<div class="vote_score">0</div></div>';
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Form</title>
    <link rel="stylesheet" type="text/css" href="css/form.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="src/jquery.js"></script>
    <script type="text/javascript" src="js/form.js"></script>
</head>
<body>
    <form action="index.php" method="post">
        <input name="name" type="hidden" value="<?php echo $name; ?>">
        <button id="return_home">Back</button>
    </form>
    <div id="first_display" class="display_wrap">
        <form action="form.php" method="get">
            <input type="hidden" name="name" value="<?php echo $name; ?>">
            <input name="image" id="set_image_input" placeholder="enter image url here" type="text"></input>
            <button name="set_image" id="set_image" value=1>Set image</button>
        </form>
        <img id="form_photo" src="<?php echo $img; ?>">
        <div>
            <label for="input_text" id="input_text_label">In 3 words, <?php echo $name; ?> is</label>
            <form action="form.php" method="get">
                <input id="input_text" name="statement" placeholder="enter a three word statement" style="text-align: center;" type="text"></input>
                <input type="hidden" name="name" value="<?php echo $name; ?>">
                <button id="input_submit">enter</button>
            </form>
        </div>
    </div>
    <div id="outer_wrap">
        <div id="id_vote_boxes">
            <div id="category_wrap">
                <form action="form.php" method="get">
                    <input type="hidden" name="name" value="<?php echo $name; ?>">
                    <?php

                    if ($sort == "new") {
                        echo '<button class="category_button" id="category_hot" name="sort" value="score">hot</button>';
                    } else {
                        echo '<button class="category_button category_pressed" id="category_hot" name="sort" value="score">hot</button>';
                    }

                    ?>
                </form>
                <form action="form.php" method="get">
                    <input type="hidden" name="name" value="<?php echo $name; ?>">
                    <?php

                    if ($sort == "new") {
                        echo '<button class="category_button category_pressed" id="category_recent" name="sort" value="new">recent</button>';
                    } else {
                        echo '<button class="category_button" id="category_hot" name="sort" value="new">recent</button>';
                    }

                    ?>
                </form>
            </div>
            <?php echo $html; ?>
        </div>
    </div>
    <form id="form_vote">
        <input type="hidden" id="id_id" name="id">
        <input type="hidden" id="id_name" name="name">
        <input type="hidden" id="id_score" name="score">
        <input type="hidden" id="id_up" name="up">
    </form>
</body>
</html>