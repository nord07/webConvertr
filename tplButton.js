//Переключатель с алгебры на кортежи и на оборот
// $('#alg').click(function(){
//     $.ajax({ 
//           type: "post",
//           success: function() {
//              location.href = "tpl.php";
//           }
//     });
//  });
 
 $('#tpl').click(function(){
    $.ajax({ 
          type: "post",
          success: function() {
             location.href = "index.php";
          }
    });
 });
 //Принять запрос
 $('#applyquery').click(function(){
    $.ajax({ 
          type: "post",
          url: "tplButton.php",
          data: {button: $('#applyquery').val(), querydb: $('#select option:selected').html()},
          success: function(response) {
             var obj = JSON.parse(response);
             var response1 = obj['NameQuery'].replace(/"/g, "").split("_");
             $('.group').val(response1[1]);
             $('.lastname').val(response1[2]);
             $('.nq').val(response1[0]);
             $('.descquery').html(obj['DescQuery']);
             $('.typevar').html(obj['TableVar']);
             $('.targetlist').html(obj['GoalList']);
             $('.algquery').html(obj['QueryBody']);
             $('.querysql').html('');
          }
    });
 });
 
 //Сохранить запрос; 
 $('#save-query').click(function(){
    $.ajax({ 
          type: "post",
          url: "tplButton.php",
          data: {button: $('#save-query').val(), nq: $('.nq').val(), group: $('.group').val(), lastname: $('.lastname').val(), 
          typevar: $('.typevar').val(), targetlist: $('.targetlist').val(), algquery: $('.algquery').val(), descquery: $('.descquery').val()},
          success: function() {
             alert("Запрос успешно сохранился !");
             location.reload()
          }
    });
 });
 
 //Изменить запрос
 $('#change-query').click(function(){
    $.ajax({ 
          type: "post",
          url: "tplButton.php",
          data: {button: $('#change-query').val(), nq: $('.nq').val(), group: $('.group').val(), lastname: $('.lastname').val(), 
          typevar: $('.typevar').val(), targetlist: $('.targetlist').val(), algquery: $('.algquery').val(), descquery: $('.descquery').val()},
          success: function() {
             alert("Запрос успешно изменился !");
             location.reload()
          }
    });
 });
 
 //Удалить запрос
 $('#delete-query').click(function(){
    $.ajax({ 
          type: "post",
          url: "tplButton.php",
          data: {button: $('#delete-query').val(), nq: $('.nq').val(), group: $('.group').val(), lastname: $('.lastname').val()},
          success: function() {
             alert("Запрос успешно удалился !");
             location.reload()
          }
    });
 });
 
 //Генерировать SQL
 $('#g-sql').click(function(){
    $.ajax({ 
          type: "post",
          url: "tplGenSQL.php",
          data: $('form').serialize(),
          success: function(response) {
             $('.querysql').html(response);
             $('.list-columns').html()
          }
    });
 });
 //Выполнить SQL
 $('#exe-sql').click(function(){
    $.ajax({ 
          type: "post",
          url: "tplButton.php",
          //data: $('#exe-sql').val(),
          //data: $('form').serialize(), 
          //button: $('#exe-sql').val(),
          data: {button: $('#exe-sql').val(), query: $('.querysql').val()},
          success: function(response) {
             $('#results').html(response);
          }
    });
 });
 //Создать VIEW
 $('#create-view').click(function(){
   $.ajax({ 
         type: "post",
         url: "tplButton.php",
         data: {button: $('#create-view').val(), select: $('#select option:selected').html(), query: $('.querysql').val()},
         success: function() {
            alert("Представление успешно создано !");
         }
   });
});