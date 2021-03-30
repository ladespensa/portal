
function _export(){
	  
    $('.table').tableExport({type:'excel',
                   fileName: 'Corte',
                   mso: {fileFormat:'xlsx',
                         worksheetName: ['Repartidores']}});

}


function setPago(id,monto){

  var ID = id;
  var pago = monto;

  var confirmar = confirm("Confirmar recepcion de pago de repartidor: $"+pago);

  if(confirmar){
          
      var data = {
                  accion: 'UPDATEPAGO',
                  id: ID,
                  pago: pago
              }
 
              Pace.start();

      $.ajax({
          type: "POST",
          cache: false,
          url: "process.php",
          data: { data: data },
          success: function (data) {


            Pace.stop();

              
                 
               if(data=="EXITOSO"){
                  
                  alert("PAGO EXITOSO");
                  Buscar();

                  }else if(data=="ERROR"){

                  alert("ERROR al registrar el PAGO.");
                 
                }
          }
      });
  }
}


function runScript(e) {
  //See notes about 'which' and 'key'
  if (e.keyCode == 13) {
      Buscar();
      return false;
  }
  }


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
                  accion: 'BUSCARADEUDOS',
                  search: $("#search").val(),
                  fecha: $('#date').val().split('/').reverse().join('/'),
                  repartidor: $("#repartidor").val()   
              }

              Pace.start();

            $.ajax({
                  url: "process.php",
                  type: "POST",
                  data: {data:data},                    
                  success: function(data)
                          {
                              
                          //console.log(data);

                          data = data.split("|");
                                
                          Pace.stop();

                          $('#grid').html(data[0]);
                          $('#subtotal').html(data[1]);
                          $('#envio').html(data[2]);
                          $('#comisiont').html(data[3]);
                          $('#total').html(data[4]);
                          $('#efectivo').html(data[5]);
                          $('#pago').html(data[6]);
                          $('#adeudo').html(data[7]);
                                
                                
                          },
                          error: function() 
                          {
                            Pace.stop();
                             } 	        
           });

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