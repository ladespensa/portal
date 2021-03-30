
function _export(){
	  
      $('.table').tableExport({type:'excel',
                     fileName: 'Adeudos_tiendas',
                     mso: {fileFormat:'xlsx',
                           worksheetName: ['Tiendas']}});

}


function setPago(id){

    var ID = id;
    var pago = prompt("Ingresar monto de pago repartidor:");

    if(pago != null && pago!="" ){
            
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
                    accion: 'BUSCARCORTE',
                    search: $("#search").val(),
                    tienda: $("#tienda").val()   
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
                            $('#adeudo').html(data[1]);
                                  
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