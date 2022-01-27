<?php
    include "connection.php";
    //Выводит список запросов
    if($_POST['action'] == 'query') {
        $sql = $_POST['sql'];
        $result = mysqli_query($conn, $sql);
    
        echo "<select name=querydb id=select autocomplete=off>";
    
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option id=option>" . $row['NameQuery'] . "</option>";
        }
    
        echo "</select>";
    }
    
    //Выводит список таблиц
    if($_POST['action'] == 'tables') {
        $sql = $_POST['sql'];
        $result = mysqli_query($conn, $sql);

        echo "<select name=querydb id=select autocomplete=off>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option id=option>" . $row['Tables_in_students'] . "</option>";
        }

        echo "</select>";
    }

    //Вывод столбцов таблицы
    if($_POST['button'] == 'Генерировать SQL'){
        
        $select = $_POST['select'];
        $sql = "SELECT TableVar FROM `querytupl` where NameQuery = '$select'";
        $result = mysqli_query($conn, $sql);
        $result_query = mysqli_fetch_assoc($result);

        $array = explode(",", $result_query['TableVar']);

        foreach ($array as $value) {

            $name_tables = strtok($value,' ');
            $word = substr($value, -1);
            
            $sql1 = "SHOW COLUMNS FROM `$name_tables`";
            $result1 = mysqli_query($conn,$sql1);

            echo "<select name=name-columns id=select1 autocomplete=off>";
                while($row = mysqli_fetch_array($result1)){
                    echo "<option id=option>" . $word. '.' . $row['Field'] . "</option>";
        }
            echo "</select>";
            
        }
    }
?>