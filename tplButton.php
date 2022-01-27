<?php
    include "connection.php";
    
    //В перменную записывается название кнопки которую нажали
    $Button = $_POST['button'];

//Если нажали кнопку Принять запрос
    if($Button == "Принять запрос") {
        $optionActive = $_POST['querydb'];
        $sql = "SELECT * FROM `querytupl` where NameQuery = '$optionActive'";
        $result = mysqli_query($conn, $sql);
        $result_query = mysqli_fetch_assoc($result);
        echo json_encode($result_query);
    }

//Если нажали кнопку Сохранить запрос
    if($Button == "Сохранить запрос"){
        $nq = $_POST['nq'];
        $g = $_POST['group'];
        $ln = $_POST['lastname'];
        $tv = $_POST['typevar'];
        $tl = $_POST['targetlist'];
        $qb = $_POST['algquery'];
        $dq = $_POST['descquery'];

        $nameQuery = $nq . "_" . $g . "_" . $ln;

        $sql = "INSERT INTO querytupl (`NameQuery`, `TableVar`, `GoalList`, `QueryBody`, `DescQuery`) VALUES ('"
            . $nameQuery ."', '" 
            . $tv ."', '" 
            . $tl ."', '" 
            . $qb ."', '" 
            . $dq . "')";
            $result_query = mysqli_query($conn, $sql);
    }

//Если нажали кнопку Изменить запрос
    if($Button == "Изменить запрос"){
        $nq = $_POST['nq'];
        $g = $_POST['group'];
        $ln = $_POST['lastname'];

        $nameQuery = $nq . "_" . $g . "_" . $ln;

        $sql = "UPDATE querytupl SET 
        TableVar = '" . $_POST['typevar'] . "', 
        GoalList = '" . $_POST['targetlist'] . "', 
        QueryBody = '" . $_POST['algquery'] ."', 
        DescQuery = '" . $_POST['descquery'] . "' 
        WHERE NameQuery = '" . $nameQuery  . "'";

        $result_query = mysqli_query($conn, $sql);
    }

//Если нажали кнопку Удалить запрос
    if($Button == "Удалить запрос"){
        $nq = $_POST['nq'];
        $g = $_POST['group'];
        $ln = $_POST['lastname'];

        $nameQuery = $nq . "_" . $g . "_" . $ln;

        $sql = "DELETE FROM querytupl WHERE NameQuery='$nameQuery'";
        $result_query = mysqli_query($conn, $sql);
    }

//Если нажали кнопку Выполнить SQL
    if($Button == "Выполнить SQL"){
        //$sql = $_POST['action'];
        
        $sql = $_POST['query'];
        echo "<table width=100% cellpadding=5>";
        echo "<tr align=center bgcolor=#808080>";
        $result_query = mysqli_query($conn, $sql);
        echo "<br>";
        while($name = mysqli_fetch_field($result_query) -> name)
        {   
            echo "<th>".$name."</th>";

        }

        echo "</tr>";

        while($get_query = mysqli_fetch_assoc($result_query))
        { 

        echo "<tr align=center bgcolor=#cccccc>";
        foreach($get_query as $k => $val)
            {
                echo "<td>".$val. "</td>"; 
            }

        echo "</tr>";

        }
        echo "</table>";
    }
    
//Если нажали кнопку создать View
if($Button == "Создать View"){
    $nameView = $_POST['select'];
    $sqlView = $_POST['query'];
    $sql = "create algorithm = UNDEFINED view `" . $nameView . "` as " . $sqlView;
    $result_query = mysqli_query($conn, $sql);
}
?>