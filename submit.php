<?php

require_once "utility.php";

session_start();

$name = $html = $up = "";

if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['up']) && isset($_POST['score'])) {
	$id_id = $_POST['id'];
	$name = $_POST['name'];
	$score = $_POST['score'];
	$up = $_POST['up'];
	if ($up && !isset($_SESSION[strval($id_id)."f"])) {
		$_SESSION[strval($id_id)."f"] = 1;
		queryMysql('UPDATE `Profile` SET `Score`='.($score + 1).' WHERE `ID`='.$id_id);
	} else if (!$up && !isset($_SESSION[strval($id_id)."f"])) {
		$_SESSION[strval($id_id)."f"] = 0;
		queryMysql('UPDATE `Profile` SET `Score`='.($score - 1).' WHERE `ID`='.$id_id);
	} else if ($up && !$_SESSION[strval($id_id)."f"]) {
		$_SESSION[strval($id_id)."f"] = 1;
		queryMysql('UPDATE `Profile` SET `Score`='.($score + 2).' WHERE `ID`='.$id_id);
	} else if (!$up && $_SESSION[strval($id_id)."f"]) {
		$_SESSION[strval($id_id)."f"] = 0;
		queryMysql('UPDATE `Profile` SET `Score`='.($score - 2).' WHERE `ID`='.$id_id);
	} else if ($up && isset($_SESSION[strval($id_id)."f"])) {
		unset($_SESSION[strval($id_id)."f"]);
		queryMysql('UPDATE `Profile` SET `Score`='.($score - 1).' WHERE `ID`='.$id_id);
	} else if (!$up && isset($_SESSION[strval($id_id)."f"])) {
		unset($_SESSION[strval($id_id)."f"]);
		queryMysql('UPDATE `Profile` SET `Score`='.($score + 1).' WHERE `ID`='.$id_id);
	}
}

if (isset($_SESSION["sort"])) {
    $sort = $_SESSION["sort"];
} else {
    $_SESSION["sort"] = "new";
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

echo $html;

?>