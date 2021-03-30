
function _export(){
	  
      $('.table').tableExport({type:'excel',
                     fileName: 'Comprobantes_tiendas',
                     mso: {fileFormat:'xlsx',
                           worksheetName: ['Tiendas']}});
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



function CambiarTienda(id){

    window.location.href="comprobantes_repartidores.php?id="+id;
}


function Buscar(){

        var data = {
                    accion: 'BUSCARCOMPROBANTESREPARTIDOR',
                    search: $("#search").val(),
                    repartidor: $("#repartidor").val(),
                    fecha: $('#date').val().split('/').reverse().join('/')   
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


  function Open(url,title) {

    var top = window.screen.height - 900;
    top = top > 0 ? top/2 : 0;
            
var left = window.screen.width - 900;
    left = left > 0 ? left/2 : 0;

var uploadWin = window.open(url,title,"width=900,height=600" + ",top=" + top + ",left=" + left);
    uploadWin.moveTo(left, top);
    uploadWin.focus();
   
  }