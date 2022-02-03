<?php

    require("lib/header.inc.php");
    require_once("utils/_init.php");
    // $users = $userStorage->findAll();
    $teams = $teamStorage->findAll();
    $matches = $matchStorage->findAll();
    $comments = $commentStorage->findAll();
    $teamid = $_GET["id"];
    foreach( $teams as $team => $id){
        if($teamid == $id["id"]){
            $teamname = $id["name"]; 
        }
    }

    function getName($id){
        global $teams;
        foreach($teams as $team => $t){
            if($id == $t["id"]){
                return $t["name"];
            }
        }
    }
    $errorsForEmpty = [];

    if (isset($_POST["comments"])){
        if(trim($_POST["comments"]) == ""){
            $errorsForEmpty[] = "Text is empty";
        } else {
            $commentStorage ->add([
                    "author" => $auth->authenticated_user()["username"],
                    "teamid" => $_GET["id"],
                    "comment" => $_POST["comments"]
            ]);
            $comments = $commentStorage->findAll();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?= $teamname?></title>
</head>
<body>
    <h1>Team: <?= $teamname?></h1>

    <table>
        <thead id="head">
            <tr>
                <td>Home</td>
                <td>Score</td>
                <td>Away</td>
                <td>Date</td>
                <?php if ($auth->authorize(["admin"])):?>
                    <td>Modifications</td>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($matches as $match => $m):?>
                <?php if ($teamid == $m["home"][0]["id"] || $teamid == $m["away"][0]["id"]):?>
                <tr>
                    <?php if (getname($m["home"][0]["id"]) == $teamname):?>
                        <td style="font-weight: bold"><?=getname($m["home"][0]["id"])?></td>
                    <?php else: ?>
                        <td><?=getname($m["home"][0]["id"])?></td>
                    <?php endif; ?>
                    <?php if (array_key_exists("score", $m["home"][0])):?>
                        <?php if(getname($m["home"][0]["id"]) == $teamname && $m["home"][0]["score"] > $m["away"][0]["score"]):?>
                            <td style="background-color: rgba(0, 255, 0, 0.3);"><?= $m["home"][0]["score"]?> - <?=$m["away"][0]["score"]?></td>
                        <?php elseif (getname($m["away"][0]["id"]) == $teamname && $m["away"][0]["score"] > $m["home"][0]["score"]):?>
                            <td style="background-color: rgba(0, 255, 0, 0.3);"><?= $m["home"][0]["score"]?> - <?=$m["away"][0]["score"]?></td>
                        <?php elseif (getname($m["home"][0]["id"]) == $teamname && $m["home"][0]["score"] == $m["away"][0]["score"]):?>
                            <td style="background-color: rgba(246, 233, 49, 0.4)"><?= $m["home"][0]["score"]?> - <?=$m["away"][0]["score"]?></td>
                        <?php elseif (getname($m["away"][0]["id"]) == $teamname && $m["away"][0]["score"] == $m["home"][0]["score"]):?>
                            <td style="background-color: rgba(246, 233, 49, 0.4)"><?= $m["home"][0]["score"]?> - <?=$m["away"][0]["score"]?></td>
                        <?php elseif(getname($m["home"][0]["id"]) == $teamname && $m["home"][0]["score"] < $m["away"][0]["score"]):?>
                            <td style="background-color: rgba(255, 0, 0, 0.4);"><?= $m["home"][0]["score"]?> - <?=$m["away"][0]["score"]?></td>
                        <?php elseif (getname($m["away"][0]["id"]) == $teamname && $m["away"][0]["score"] < $m["home"][0]["score"]):?>
                            <td style="background-color: rgba(255, 0, 0, 0.4);"><?= $m["home"][0]["score"]?> - <?=$m["away"][0]["score"]?></td>
                        <?php else:?>
                            <td><?= $m["home"][0]["score"]?> - <?=$m["away"][0]["score"]?></td>
                        <?php endif;?>
                    <?php else: ?>

                        <td>Soon...</td>

                    <?php endif; ?>
                    <?php if (getname($m["away"][0]["id"]) == $teamname):?>
                        <td style="font-weight: bold"><?=getname($m["away"][0]["id"])?></td>
                    <?php else: ?>
                        <td><?=getname($m["away"][0]["id"])?></td>
                    <?php endif; ?>
                    <td><?=$m["date"]?></td>
                    
                    <?php if ($auth->authorize(["admin"])):?>
                        <td><a href="modify.php?idd=<?=$match?>&home=<?=$m["home"][0]["id"]?>&away=<?=$m["away"][0]["id"]?>&dt=<?=$m["date"]?>">Modify</a></td>
                    <?php endif; ?>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
        </tbody>
    </table>
    <br><br><br><br><br>
    <h2>Comments</h2>
    <?php if ($auth->authorize(["user"]) || $auth->authorize(["admin"])): ?>
    <br><br>
    <div>
    <?php foreach($errorsForEmpty as $error) : ?>
        <div class="alert alert-warning" role="alert"><?= $error ?></div>
    <?php endforeach; ?>
    </div>
    <form action="" method="post">
        <div>
        <textarea name="comments" name="comments"></textarea>
        </div>
        <input type="submit" value="Submit">
    </form>
    <?php else:?>
        <div class="alert alert-warning" role="alert">Please log in to leave a comment!</div>
    <?php endif;?>

    <table id="comment">
    <?php foreach ($comments as $id => $comment):?>
        <?php if ($comment["teamid"] == $teamid ):?>
        <tr>
            <td><?= $comment["author"]?> says:</td>
            <td><?= $comment["comment"]?></td>
            <td>
            <?php if($auth->authorize(["admin"])):?>
                <div class="float-right">
                    <a class="btn btn-primary" href="delete.php?id=<?=$teamid?>&cmntId=<?=$id?>">Delete Comment</a>
                </div>
            <?php endif; ?>
            </td>
        </tr>
        
        <?php endif;?>
    <?php endforeach; ?>
    </table>
</body>
</html>