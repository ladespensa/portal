
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


          var id = $('#tienda').val();

       
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
                                        window.location.href="index.php?alert=exitoso&id="+id;
                                    }else{
                                          alert("Hubo un error, intentalo nuevamente:"+data);
                                    }
                                    
                                
                            
                            },
                            error: function() 
                            {
                                Pace.stop();
                              alert("Hubo un error!");
                            } 	        
             });
                
        }));