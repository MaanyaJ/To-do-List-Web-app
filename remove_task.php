<?php
    if(isset($_POST['id'])){
        require "config.php";
        $id = $_POST['id'];
        if(empty($id)){
            echo 0;
        }
        else{
            $stmt = $conn->prepare("DELETE FROM Tasks WHERE Task_ID=?");
            $res = $stmt->execute([$id]);
            if($res){
                echo 1;
            }
            else{
                echo 0;
            }
            $conn = NULL;
            exit();
        }
    }
    else{
        header("Location: dashboard.php?msg=success");
    }
?>