
function runScript(e) {
    //See notes about 'which' and 'key'
    if (e.keyCode == 13) {
        Buscar();
        return false;
    }
    }


$(document).ready(function(){


 
  Buscar();

  if(gup("alert")=="exitoso"){

    $('.toast').toast('show');

  }
         
     
});


function Buscar(){


        var data = {
                    accion: 'BUSCAR',
                    search: $("#search").val(),            
                }


                Pace.start();

              $.ajax({
                    url: "process.php",
                    type: "POST",
                    data: {data:data},                    
                    success: function(data)
                            {
                                
                              console.log(data);
                                  
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