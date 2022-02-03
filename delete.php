<?php
    require_once("utils/_init.php");

    if (!$auth->authorize(["admin"])) {
        redirect("index.php");
      }
    
    if (verify_get("cmntId", "id")){
        $teamId = $_GET["id"];
        $commentId = $_GET["cmntId"];

        $comments = $commentStorage->findAll(["teamid" => $teamId]);
        foreach ($comments as $id => $comment) {
            if($id == $commentId){
                unset($comments[$id]);
                $items = $commentStorage->findAll(["teamid" => $teamId]);
                foreach ($items as $idd => $item) {
                    if($idd == $commentId){
                        $commentStorage->delete($commentId);
                        break;
                    }
                }
                break;
            }
        }
    }
    redirect("teampage.php?id=" . $_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>deleting...</title>
</head>
<body>
    
</body>
</html>