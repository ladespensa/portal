
function _export(){
	  
    $('.table').tableExport({type:'excel',
                   fileName: 'Codigos',
                   mso: {fileFormat:'xlsx',
                         worksheetName: ['Codigos']}});

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
                    accion: 'BUSCAR',
                    search: $("#search").val(),
                    fecha: $('#date').val().split('/').reverse().join('/')    
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