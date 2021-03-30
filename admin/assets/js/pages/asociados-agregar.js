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


        var map="";
        var marker="";
        var myLatlng="";

        var latitud = 0;
        var longitud = 0;


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



      



       