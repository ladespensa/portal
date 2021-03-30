function runScript(e) {
    //See notes about 'which' and 'key'
    if (e.keyCode == 13) {
        Buscar();
        return false;
    }
    }


$(document).ready(function(){


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
                    categoria: $("#categorias").val(),               
                }


       // $('#grid').loading({message:'Espere..' });

       Pace.start();


              $.ajax({
                    url: "process.php",
                    type: "POST",
                    data: {data:data},                    
                    success: function(data)
                            {
                                
                              
                              Pace.stop();
                              
                            //$('#grid').loading('stop');

                            $('#grid').html(data);
                                  
                                  
                            },
                            error: function() 
                            {
                              alert("Hubo un error");
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