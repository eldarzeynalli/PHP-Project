<?php
    require("lib/header.inc.php");
    $matches = $matchStorage->findAll();
    $teams = $teamStorage->findAll();
    $teamHome = $_GET["home"];
    $teamAway = $_GET["away"];
    $date = $_GET["dt"];

    function getName($id){
        global $teams;
        foreach($teams as $team => $t){
            if($id == $t["id"]){
                return $t["name"];
            }
        }
    }

    $errorsForModify = [];

    if(isset($_POST['date']) && isset($_POST['scoreteam2']) && isset($_POST['scoreteam1']) ){
        $tmp_date = $_POST['date'];
        $scoreteam2 = $_POST['scoreteam2'];
        $scoreteam1 = $_POST['scoreteam1'];
        
        if($scoreteam2 < 0 || $scoreteam1 < 0 ){
            $errorsForModify[] = "Invalid number of scores. Scores should be positive";
        }
        
        if(empty($errorsForModify)){
            $matches[$_GET['idd']]['date'] = $tmp_date;
            $matches[$_GET['idd']]['home'][0]['score'] = $scoreteam1;
            $matches[$_GET['idd']]['away'][0]['score'] = $scoreteam2;
    
            $matchStorage->update($_GET['idd'], $matches[$_GET['idd']]);
            $matches = $matchStorage->findAll();
    
            redirect("index.php");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify</title>
</head>
<body>
    <h1>Modification settings</h1>
  
    <?php foreach ($matches as $id => $match):?>
        <?php if($match["home"][0]["id"] == $teamHome && $match["away"][0]["id"] == $teamAway && $match["date"] == $date):?>
            <form class="col-md-6 col-xs-12" method="post">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input class="form-control" type="date" name="date" id="date" value="<?= $_POST["date"] ?? $date ?>">
                </div>
                <div class="form-group">
                    <label for="scoreteam1">Score of the <?=getName($teamHome)?></label>
                    <input class="form-control" type="number" name="scoreteam1" id="scoreteam1" value="<?= $match["home"][0]["score"] ?>">
                </div>
                <div class="form-group">
                    <label for="scoreteam2">Score of the <?=getName($teamAway)?></label>
                    <input class="form-control" type="number" name="scoreteam2" id="scoreteam2" value="<?= $match["away"][0]["score"] ?>">
                </div>
                <button class="btn btn-primary">Submit</button>
            </form>
        <?php endif;?>
    <?php endforeach; ?>
     
</body>
</html>