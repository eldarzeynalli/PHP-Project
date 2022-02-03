<?php 
    require("lib/header.inc.php");
    require_once("utils/_init.php");
    // $users = $userStorage->findAll();
    $teams = $teamStorage->findAll();
    $matches = $matchStorage->findAll();
    
    $arr = [];
    $count = 0;
    foreach ($matches as $match => $date){
        
        if(array_key_exists("score", $date["home"][0])){
            array_push($arr, $date["date"]);
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
    function compareByTimeStamp($time1, $time2)
{
    if (strtotime($time1) < strtotime($time2))
        return 1;
    else if (strtotime($time1) > strtotime($time2)) 
        return -1;
    else
        return 0;
}
    usort($arr, "compareByTimeStamp");
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <style>
        body {
    background-image: url('img/balls.png');
    font-family: "Poppins", sans-serif;
    height: 100vh;
  }
    </style>
    <title>ELTE STADIUM</title>
    
</head>
<body id="boddy">
        <h2 style=" color: #007bff;
	                font-size: 80px;
	                -webkit-text-stroke: 1px white; font-family: Copperplate,fantasy;">
                    Teams</h2>
        <table style="background-color: white; border: 1px solid black;">
            <thead id="head">
                <tr>
                    <td>Name</td>
                    <td>City</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($teams as $team => $id): ?>
                    <tr>
                        <td><a href="teampage.php?id=<?=$id['id']?>"><?=$id["name"]?></a></td>
                        <td><?=$id["city"]?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <br><br><br><br><br>
        <h2 style=" color: #007bff;
	font-size: 80px;
	-webkit-text-stroke: 1px white; font-family: Copperplate,fantasy;">Last 5 Matches</h2>
        <?php $cnt = 0?>
        <?php ?>
        <table style="background-color: white; border: 1px solid black;">
            <thead  id="head"> 
                <tr>
                   <td>Home</td>
                   <td>Score</td>
                   <td>Away</td>
                   <td>Date</td>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0 ;$i < 5; $i++):
                        foreach ($matches as $match => $id):
                            if ( $id["date"] == $arr[$i] ){?>
                                <tr>
                                    <td><?= getName($id["home"][0]["id"]) ?></td>
                                    <td> <?= $id["home"][0]["score"]?>-<?= $id["away"][0]["score"]?></td>
                                    <td><?=getName($id["away"][0]["id"])?></td>
                                    <td><?= $id["date"]?></td>
                                </tr>
                            <?php }
                        endforeach;
                    endfor; ?>
             </tbody>
        </table>    



    
</body>
</html>