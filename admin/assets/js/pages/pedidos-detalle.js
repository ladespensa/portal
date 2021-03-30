



function ReanudarPedido(id_pedido){
      
  var confirmacion = confirm("¿Desea REANUDAR el Pedido?");
  var id_repartidor = $('#repartidor').val();
  
      var data = {
                accion: 'REANUDARPEDIDO',
                id_pedido: id_pedido
            }
            
      if(!confirmacion)
      return;

      var pedido = prompt("Confirme el numero de pedido", "");

      if(pedido!=id_pedido){
      alert("No se puede REANUDAR ya que el pedido ingresado no coincide.");
      }else{

      Pace.start();

      $.ajax({
        type: "POST",
        cache: false,
        url: "process.php",
        data: { data: data },
        success: function (data) {

            Pace.stop();
              
            if(data=="EXITOSO"){
                
               // window.location.href='index.php?accion=delete&message=Asociado Eliminado';
               alert("¡PEDIDO REANUDADO!");
               location.reload();
                
                }else if(data=="ERROR"){

                alert("ERROR al intentar asignar al repartidor.");
              
              }
        }
      });
    }

}


function CancelarPedido(id_pedido,id_estatus){
      
  var confirmacion = confirm("¿Desea CANCELAR el Pedido?");
  var id_repartidor = $('#repartidor').val();
  
      var data = {
                accion: 'CANCELARPEDIDO',
                id_pedido: id_pedido,
                id_estatus: id_estatus,
            }
            
      if(!confirmacion){

       location.reload();
      
      }else{

      var pedido = prompt("Confirme el numero de pedido", "");

      if(pedido!=id_pedido){
      alert("No se puede CANCELAR ya que el pedido ingresado no coincide.");
      location.reload();
      }else{

      Pace.start();

      $.ajax({
        type: "POST",
        cache: false,
        url: "process.php",
        data: { data: data },
        success: function (data) {

            Pace.stop();
              
            if(data=="EXITOSO"){
                
               // window.location.href='index.php?accion=delete&message=Asociado Eliminado';
               alert("¡PEDIDO CANCELADO!");
               location.reload();
                
                }else if(data=="ERROR"){

                alert("ERROR al intentar asignar al repartidor.");
              
              }
        }
      });
    }
  }

}


function AsignarRepartidor(id_pedido){
      
      var confirmacion = confirm("Desea Asignar el Repartidor?");
      var id_repartidor = $('#repartidor').val();

          var data = {
                    accion: 'ASIGNARREPARTIDOR',
                    id_repartidor: id_repartidor,
                    id_pedido: id_pedido
                }
                
          if(!confirmacion){

            location.reload();

          }else{
               
          

          Pace.start();

          $.ajax({
            type: "POST",
            cache: false,
            url: "process.php",
            data: { data: data },
            success: function (data) {

                Pace.stop();
                  
                if(data=="EXITOSO"){
                    
                   // window.location.href='index.php?accion=delete&message=Asociado Eliminado';
                    
                    }else if(data=="ERROR"){

                    alert("ERROR al intentar asignar al repartidor.");
                  
                  }
            }
          });

        }
      
   }


   function CambiarEstatus(id_pedido){

      var confirmacion = confirm("Confirma cambiar el estatus del Pedido?");
      var id_estatus = $('#estatus').val();


      if(!confirmacion){
        location.reload();
      }else{


      if(id_estatus==6 || id_estatus==7){
        CancelarPedido(id_pedido,id_estatus);
      }else{
      
      var data = {
                accion: 'ASIGNARESTATUS',
                id_estatus: id_estatus,
                id_pedido: id_pedido
            }
    
      Pace.start();

      $.ajax({
        type: "POST",
        cache: false,
        url: "process.php",
        data: { data: data },
        success: function (data) {

            Pace.stop();
              
            if(data=="EXITOSO"){
                
               // window.location.href='index.php?accion=delete&message=Asociado Eliminado';
                
                }else if(data=="ERROR"){

                alert("ERROR al intentar cambiar el estatus del Pedido.");
              
              }
        }
      });
    }
  }

}