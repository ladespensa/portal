
$(document).ready(function(){

    //$('#date').val(getDateNow());
    //$('#date2').val(getDateNow());
  
    $('.input-group.date').datepicker({
      format: "dd/mm/yyyy",
      autoclose: true,
      todayHighlight: true,
      date: getDateNow()
    });
  
  
           
  });



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



function Eliminar(ID){


    var eliminar = confirm('¿Desea eliminar el codigo de descuento?');

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
              
              window.location.href='index.php?accion=delete&message=Codigo Eliminado';
              
              }else if(data=="ERROR"){

              alert("ERROR al intentar eliminar el codigo.");
             
            }
      }
  });


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
                            
                            Pace.stop();
                          alert("Hubo un error!");
                        } 	        
         });
            
    }));


    



  



   