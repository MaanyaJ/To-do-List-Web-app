<?php
    if(isset($_POST['Task'])){
        require "config.php";
        $task = $_POST['Task'];
        if(empty($task)){
            header("Location: dashboard.php?msg=error");
        }
        else{
            $stmt = $conn->prepare("INSERT INTO Tasks(Task) VALUES(?)");
            $res = $stmt->execute([$task]);
            if($res){
                header("Location: dashboard.php?msg=success");
            }
            else{
                header("Location: dashboard.php");
            }
            $conn = NULL;
            exit();
        }
    }
    else{
        header("Location: dashboard.php?msg=success");
    }
?>