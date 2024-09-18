<?php
require_once "config.php";
$todos_result = $conn->query("SELECT * FROM tasks ORDER BY Task_ID DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODO List</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main-section">
        <div class="add-section">
            <form action="add_task.php" method="POST" autocomplete="off">
                <?php if(isset($_GET['msg']) && $_GET['msg'] == 'error') { ?>
                    <div class="error">
                        <input type="text" name="Task" style="border-color: #ff3860;" placeholder="Please enter your todo">
                        <button type="submit">Add &nbsp; <span style="border-color:#DDD0C8">&#43;</span></button>
                    </div>
                <?php } else{ ?>
                <input type="text" name="Task" placeholder="Enter your todo here" >
                <button type="submit">Add &nbsp; <span style="border-color:#DDD0C8">&#43;</span></button>
                <?php } ?>
            </form>
        </div>
        <div class="show-todo-section">
            <?php if($todos_result->num_rows == 0){ ?>
                <div class="todo-item">
                    <div class="empty" style="color: #323232";>
                        <h1>No todos so far</h1>
                    </div>
                </div>
            <?php } ?>
            <?php while ($todo = $todos_result->fetch_assoc()) { ?>
            <div class="todo-item">
                <span id= "<?php echo $todo['Task_ID']; ?>" class="remove-to-do">x</span>
                <?php if($todo['Checked']) { ?>
                    <input type="checkbox" data-todo-id="<?php echo $todo['Task_ID']; ?>" class="check-box" checked>
                    <h2 class="checked"><?php echo $todo['Task']; ?></h2>
                <?php }else{ ?>
                    <input type="checkbox" data-todo-id="<?php echo $todo['Task_ID']; ?>" class="check-box">
                    <h2><?php echo $todo['Task']; ?></h2>
                <?php } ?>
                    <br>
                    <small>created: <?php echo $todo['Date_Time']; ?></small>
            </div>
            <?php } ?>
        </div>
    </div>
    <script src="jquery.js"></script>
<script>
    $(document).ready(function(){
    $('.remove-to-do').click(function(){
        const id = $(this).attr('id');
        const $this = $(this); 
        $.post("remove_task.php", 
            { id: id }, 
            function(data) { 
                if(data){
                    $this.parent().hide(600); 
                }
            }
        );
    });

    $(".check-box").click(function(e){
        const id = $(this).attr('data-todo-id');
        const $h2 = $(this).next(); 
        $.post('check_task.php',
        {
            id: id
        },
        (data) => {
            if(data != 'error'){
                if(data === '1'){
                    $h2.removeClass('checked');
                }
                else{
                    $h2.addClass('checked');
                }
            }
        });
    });
    });
</script>
</body>
</html>
