
function _export(){
	  
      $('.table').tableExport({type:'excel',
                     fileName: 'Usuarios',
                     mso: {fileFormat:'xlsx',
                           worksheetName: ['Usuarios']}});
  
  }
  
  
  function runScript(e) {
      //See notes about 'which' and 'key'
      if (e.keyCode == 13) {
          Buscar();
          return false;
      }
      }



   //Formulario Registro
   $("#formasociados").on('submit',(function(e) {
    e.preventDefault();
    
    Pace.start();

   
              $.ajax({
                url: "process.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                    cache: false,
                processData:false,
                success: function(data)
                        {

                          Pace.stop();
                               
                               if(data=="EXITOSO"){
                                    window.location.href="index.php?alert=exitoso";
                                }else{
                                      alert("Hubo un error, intentalo nuevamente.");
                                }
                        
                        },
                        error: function() 
                        {
                          $('#formasociados').loading('stop');
                          alert("Hubo un error!");
                        } 	        
         });
            
    }));
  
  
  
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
                      search: $("#search").val()
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