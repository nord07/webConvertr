<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>WEB-конвертер</title>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head> 
<body class="tpl" style="background-color: #E0FFFF">
    <input id="tpl" name="radio" type="radio" value="alg_kor">
    <table style="background-color: #E0FFFF" id="tb_1"  width=100% border=0px>
        <tr>
            <form id="query1" method="post" action="" name="query1">
            <td align="left"><b>КОРТЕЖИ</b></td>
            <td><b>Описание запроса</b></td>
            <td>Группа: <input class="group" name="group" type="text" maxlength="3" size="3" value="" autocomplete="off"></td>
            <td>Фамилия: <input class="lastname" name="lastname" type="text" maxlength="20" size="20" value="" autocomplete="off"></td>
            <td><b>Типы переменных</b></td>
            <td>Номер запроса: <input class="nq" name="nq" type="text" maxlength="4" size="4" value="" autocomplete="off"></td>
            <td align="right">
                <div class='name-query'></div>
            </td>
        </tr>
    </table>

    <table style="background-color: #E0FFFF" id="tb_1"  width=100% border=0px>
        <tr>
            <td>
                <img src="mephi.jpg" height="80" width="80"></img>
            </td>
            <td>
                <textarea class="descquery" name="descquery" cols="80" rows="5" style="width: 550px; height: 82px;" autocomplete="off"></textarea>
            </td>
            <td>
                <textarea class="typevar" name="typevar" cols="80" rows="5" style="width: 595px; height: 82px;" autocomplete="off"></textarea>
            </td>       
            <td align="right">
                <p><input id="applyquery" type="button" name="applyquery" value="Принять запрос" ></input></p>
                <p><input type="button" id ="save-query" value="Сохранить запрос"></input></p>
            </td>
        </tr>
    </table>

    <table width=57.4%>
        <tr>
            <td align="right">
                <div class="list-tables"></div>
            </td>
        </tr>
    </table>
    
    <table style="background-color: #E0FFFF" id="tb_1"  width=100% border=0px>
        <tr>
            <td>
                <b>Целевой список</b>
            </td>
            <td>
                <textarea name="targetlist" class="targetlist" cols="150" rows="1" style="width: 1126px; height: 18px;" autocomplete="off"></textarea>            </td>
            </td>
            <td align="right">
                <input id="change-query" type="button" value="Изменить запрос"></input>
            </td>
        </tr>
    </table>

    <table width=26.6%>
        <tr>
            <td align="right">
                <div class="list-columns"></div>
            </td>
        </tr>
    </table>

    <table style="background-color: #E0FFFF" id="tb_1"  width=100% border=0px>
        <tr>
            <td>
                <textarea class="algquery" type="text" name="algquery" cols="80" rows="13" autocomplete="off" style="width: 614px; height: 215px;"></textarea>            
            </td>
            <td>
                <textarea class="querysql" type="text" name="query" cols="80" rows="13" placeholder="Запрос на SQL" style="width: 615px; height: 215px;" autocomplete="off"></textarea>            </td>
            </td>
            <td align="right">
                <p><input type="button" value="Удалить запрос" id="delete-query" /></p>
                <p><input type="button" value="Генерировать SQL" id="g-sql" /></p>
                <p><input id="exe-sql" type="button" value="Выполнить SQL" name="exe-sql" /></p>
                <p><input id="create-view" type="button" value="Создать View" name="create-view" /></p>
            </form>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td align="right">
                <div class="list-columns"></div>
            </td>
        </tr>
    </table>

<div id="results">
</div>
<script type="text/javascript" src="tplButton.js"></script>
<script type="text/javascript" src="tplHandlers.js"></script>
</body>
</html>