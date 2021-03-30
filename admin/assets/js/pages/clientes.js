
function getDateNow(){
  
    var today = new Date();
    var dd = today.getDate();

    var mm = today.getMonth()+1; 
    var yyyy = today.getFullYear();
    if(dd<10) 
    {
        dd='0'+dd;
    } 

    if(mm<10) 
    {
        mm='0'+mm;
    } 


    today = dd+'/'+mm+'/'+yyyy;

    return today;

}


function _sendSMSCODE(){

  var telefonos = [];
  $.each($("input[name='cliente']:checked"), function(){
      telefonos.push($(this).val());
  });


  if(telefonos.length<=0){

    
    alert("Selecciona mínimo un cliente");

  }else{

    $('#btn_sms_code').html('<img src="../img/ajax-loader.gif" />');

    var data = {
      accion: 'SENDSMSCODE',        
      telefonos: telefonos,    
    }


    $.ajax({
      url: "process.php",
      type: "POST",
      data: {data:data},                    
      success: function(data)
              {

               
                $('#btn_sms_code').html('<span class="ion ion-md-send"></span>&nbsp;Enviar SMS CODE');
                alert("CODE SMS enviados..");

                deseleccionar_todo();
             
              },
              error: function() 
              {
                alert("Error al enviar SMS!");
               } 	        
});
    

  }


}

function OPEN_sendSMS(){

  var telefonos = [];
  $.each($("input[name='cliente']:checked"), function(){
      telefonos.push($(this).val());
  });


  if(telefonos.length<=0){

    
    alert("Selecciona mínimo un cliente");

  }else{
    $('#mensajes-texto').modal('show');

  }


}


function _sendMessageText(){


  var telefonos = [];
  $.each($("input[name='cliente']:checked"), function(){
      telefonos.push($(this).val());
  });

  if(telefonos.length<=0){

    $('#mensajes-texto').modal('hide');
    alert("Selecciona mínimo un cliente");

  }else{
  
   if($("#titulo_sms").val()=="" && $("#mensaje_sms").val()==""){
  
   alert("Ingresa Titulo y Mensaje");
  
   }else{
  
  
  $('#btn_send_sms').html('<img src="../img/ajax-loader.gif" />');
  
  var data = {
                    accion: 'SENDSMS',
                    titulo: $("#titulo").val(),
                    mensaje: $("#mensaje").val(),        
                    telefonos: telefonos,    
                }
  
  
        //$('#grid').loading({message:'Espere..' });
  
              $.ajax({
                    url: "process.php",
                    type: "POST",
                    data: {data:data},                    
                    success: function(data)
                            {

                              alert(data);
                           
                              $('#mensajes-texto').modal('hide');
                              $('#btn_send_sms').html('Enviar');
                              alert("SMS enviados..");
                           
                            },
                            error: function() 
                            {
                              alert("Error al enviar SMS!");
                             } 	        
             });
            }
          }

}



function _sendpush(){

if($("#titulo").val()=="" && $("#mensaje").val()==""){
alert("Ingresa Titulo y Mensaje");
}

else{


$('#btn_send').html('<img src="../img/ajax-loader.gif" />');

var data = {
                  accion: 'SENDPUSH',
                  titulo: $("#titulo").val(),
                  mensaje: $("#mensaje").val(),            
              }


      //$('#grid').loading({message:'Espere..' });

            $.ajax({
                  url: "process.php",
                  type: "POST",
                  data: {data:data},                    
                  success: function(data)
                          {
                         
                            $('#push').modal('hide');
                            $('#btn_send').html('Enviar');
                            alert("Push Notifications enviados..");
                         
                          },
                          error: function() 
                          {
                            alert("Error al enviar notificaciones!");
                           } 	        
           });
          }

}

function _export(){
    
  $('.table').tableExport({type:'excel',
                 fileName: 'Clientes',
                 mso: {fileFormat:'xlsx',
                       worksheetName: ['Clientes']}});

}


function runScript(e) {
  //See notes about 'which' and 'key'
  if (e.keyCode == 13) {
      Buscar();
      return false;
  }
  }


$(document).ready(function(){

    $('#date').val(getDateNow());
  
    $('.input-group.date').datepicker({
      format: "dd/mm/yyyy",
      autoclose: true,
      todayHighlight: true,
      date: getDateNow()
    });


    $('.input-group.date').change(function () {
      
        //var newDate = $('#date').val().split('/').reverse().join('/');
    
        Buscar();
    });

//$('.toast').hide();

Buscar();

if(gup("alert")=="exitoso"){
   
  $('.toast').toast('show');

}
       
   
});


function Buscar(){


      var data = {
                  accion: 'BUSCAR',
                  search: $("#search").val(),
                  plataforma: $("#plataforma").val(),            
              }


              Pace.start();

            $.ajax({
                  url: "process.php",
                  type: "POST",
                  data: {data:data},                    
                  success: function(data)
                          {
                                
                            Pace.stop();

                          $('#grid').html(data);
                                
                                
                          },
                          error: function() 
                          {
                            Pace.stop();
                                                       } 	        
           });

}



function seleccionar_todo(){
  for (i=0;i<document.f1.elements.length;i++)
     if(document.f1.elements[i].type == "checkbox")
        document.f1.elements[i].checked=1
}


function deseleccionar_todo(){
  for (i=0;i<document.f1.elements.length;i++)
     if(document.f1.elements[i].type == "checkbox")
        document.f1.elements[i].checked=0
}


function gup(name){

name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
var regexS = "[\\?&]"+name+"=([^&#]*)";
var regex = new RegExp(regexS);
var results = regex.exec(window.location.href);
if (results == null) {
return '';
} else {
return results[1];
}

}