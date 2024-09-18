<?php
if(isset($_POST['id'])){
    require "config.php";
    $id = $_POST['id'];
    if(empty($id)){
        echo "error";
    }
    else{
        $stmt = $conn->prepare("SELECT Task_ID, Checked FROM Tasks WHERE Task_ID=?");
        $stmt->bind_param("i", $id); 
        $stmt->execute();
        $result = $stmt->get_result(); 
        $todo = $result->fetch_assoc(); 
        $uId = $todo['Task_ID'];
        $checked = $todo['Checked'];
        $uChecked = $checked ? 0 : 1;
        $stmt = $conn->prepare("UPDATE Tasks SET Checked=? WHERE Task_ID=?");
        $stmt->bind_param("ii", $uChecked, $uId); 
        $res = $stmt->execute(); 
        if($res){
            echo $checked;
        } else {
            echo "error";
        }
        $stmt->close();
        $conn->close();
        exit();
    }
} else {
    header("Location: dashboard.php?msg=success");
    exit();
}
?>
