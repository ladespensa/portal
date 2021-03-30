
function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#previewPromoImage').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#promoImage").change(function(){
        readURL(this);
    });



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
                                Pace.stop();
                              alert("Hubo un error!");
                            } 	        
             });
                
        }));



        function Eliminar(ID){


          var eliminar = confirm('¿Desea eliminar el Tendedero?');

          var data = {
                    accion: 'ELIMINAR',
                    id: ID
                }
                
    if(!eliminar)
       return;

       Pace.start();

        $.ajax({
            type: "POST",
            cache: false,
            url: "process.php",
            data: { data: data },
            success: function (data) {


                Pace.stop();
                   
                 if(data=="EXITOSO"){
                    
                    window.location.href='index.php?accion=delete&message=Asociado Eliminado';
                    
                    }else if(data=="ERROR"){

                    alert("ERROR al intentar eliminar la empresa.");
                   
                  }
            }
        });


        }