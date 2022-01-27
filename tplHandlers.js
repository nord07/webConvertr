$.ajax({ 
    type: "post",
    url: "tplSelects.php",
    data: { action: "query", sql: "SELECT NameQuery FROM querytupl"},
    success: function(response) {
       $('.name-query').html(response);
    }
 });
 //Обработка запроса на вывод список таблиц бд
 $.ajax({ 
    type: "post",
    url: "selectsDB.php",
    data: { action: "tables", sql: "show TABLES"},
    success: function(response) {
       $('.list-tables').html(response);
    }
 });
 //
 $('#g-sql').click(function(){
    $.ajax({ 
          type: "post",
          url: "tplSelects.php",
          data: {button: $('#g-sql').val(), select: $('#select option:selected').html()},
          success: function(response) {
             $('.list-columns').html(response)
          }
    });
 });