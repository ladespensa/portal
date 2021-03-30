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
        
          $('#formasociados').loading({message:'Espere..' });

       
                  $.ajax({
                    url: "process.php",
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                        cache: false,
                    processData:false,
                    success: function(data)
                            {

                                    $('#formasociados').loading('stop');
                                   
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



        function Eliminar(ID){


          var eliminar = confirm('¿Desea eliminar el Asociado?');

          var data = {
                    accion: 'ELIMINAR',
                    id: ID
                }
                
    if(!eliminar)
       return;

    $('body').loading({message:'Espere..' });

        $.ajax({
            type: "POST",
            cache: false,
            url: "process.php",
            data: { data: data },
            success: function (data) {



                $('body').loading('stop');
                   
                 if(data=="EXITOSO"){
                    
                    window.location.href='index.php?accion=delete&message=Asociado Eliminado';
                    
                    }else if(data=="ERROR"){

                    alert("ERROR al intentar eliminar la empresa.");
                   
                  }
            }
        });


        }





        function setCoordenadas(){

            // latitud = $('#latitud').val();
            // longitud = $('#longitud').val();

             latitud = $('#latitud').val();
             longitud = $('#longitud').val();
             
             console.log("latitud:"+latitud);
             console.log("longitud:"+longitud);


             myLatlng = new google.maps.LatLng(latitud, longitud);
             marker.setPosition(myLatlng);
             map.setCenter(myLatlng);        
        
        }


        function initMap() {
                // The location of Uluru
           
                var uluru = {lat:  latitud, lng: longitud};
                
                // The map, centered at Uluru
                map = new google.maps.Map(
                    document.getElementById('map'), {zoom: 17, center: uluru});
                // The marker, positioned at Uluru
                marker = new google.maps.Marker({position: uluru, map: map});

   }