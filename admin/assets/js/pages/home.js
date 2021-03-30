var INIT_LAT = 19.415369;
var INIT_LONG = -98.140132;

var REPARTIDORES_CONECTADOS = 0;

var REPARTIDORES_ARRAY = new Object();
var CLIENTES_ARRAY = new Object();

var global_infowindows_Clientes = [];
var global_infowindows_Repartidores = [];

var mapLabel;

var marker, map, directionsService, directionsDisplay;
var $log;
var geocoder;

var infowindow;

var polylineOptionsActual = new google.maps.Polyline({
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 3
});


var Timer;


$(function() {

 
  initialize();


  $('.chat-scroll').each(function() {
    new PerfectScrollbar(this, {
      suppressScrollX: true,
      wheelPropagation: true
    });
  });

  $('.chat-sidebox-toggler').click(function(e) {
    e.preventDefault();
    $('.chat-wrapper').toggleClass('chat-sidebox-open');
  });

});


$(document).ready(function(){

  getPedidos('1,2,3,4,5');
  getRepartidores();
  Timer = setInterval(checkNuevosPedidos, 20000);

});



function getPedidos(status){

  var data = {
      accion: 'BUSCAR',
      status: status,       
    }

     
      $('#pedidos').html('');
      clearMarkers();


$.ajax({
    url: "process.php",
    type: "POST",
    data: {data:data},                    
    success: function(data)
            {
             
              //obj1 = JSON.stringify(data);
              obj_json = JSON.parse(data);

              //alert(obj_json.length); 
              //console.log(obj_json.[0]);
              

              var total = 0;
              var pendiente = 0;
              var progreso = 0;
              var entregados = 0;
              var cancelados = 0;
              var pedido;
              var coordenadas;
              var cliente;
              var estatus;
              var repartidor;
              var tienda;
              var imagen_tienda;
              var direccion;
              var alerta= false;
              

              for (var obj in obj_json) {
                     
                //console.log(obj_json[obj].PK);


                total = obj_json[obj].TODOS;
                pendiente = obj_json[obj].PENDIENTES;
                progreso = obj_json[obj].PROGRESO;
                entregados = obj_json[obj].ENTREGADOS;
                cancelados = obj_json[obj].CANCELADOS;
                
                pedido = obj_json[obj].PK;
                coordenadas = obj_json[obj].LATITUD+','+obj_json[obj].LONGITUD;
                cliente = obj_json[obj].PK_CLIENTE;
                estatus = obj_json[obj].PK_ESTATUS;
                repartidor = obj_json[obj].PK_REPARTIDOR;
                tienda = obj_json[obj].TIENDA;
                imagen_tienda = obj_json[obj].IMAGEN_TIENDA;
                direccion = obj_json[obj].DIRECCION;

                if(estatus==1){alerta=true;}

                addMarker_Ciudadano(pedido, coordenadas, cliente, repartidor, estatus,tienda,imagen_tienda,direccion);
              
              }


              $('#todos').html(total);
              $('#pendientes').html(pendiente);
              $('#progreso').html(progreso);
              $('#entregados').html(entregados);
              $('#cancelados').html(cancelados);

              console.log(cancelados);

              if(alerta){
                //alert("NUEVO PEDIDO");

                $.playSound("https://acmarket.expressmyapp.com/admin/sounds/service-in.mp3");

              }
         
            },
            error: function() 
            {
              //alert("Hubo un error");
            } 	        
});

}



//-----BUSCAR REPARTIDORES----//

function getRepartidores(){

  var data = {
      accion: 'BUSCARREPARTIDORES',
         
    }

      clearMarkersRepartidores();

      
$.ajax({
    url: "process.php",
    type: "POST",
    data: {data:data},                    
    success: function(data)
            {
             
             
              //obj1 = JSON.stringify(data);
              obj_json = JSON.parse(data);

              //alert(obj_json.length); 
              //console.log(obj_json.[0]);
              
              var pedido;
              var coordenadas;
              var cliente;
              var estatus;
              var repartidor;              
              var direccion;
              
              

              for (var obj in obj_json) {
                     
                //console.log(obj_json[obj].PK);
                
                pedido = obj_json[obj].PK;
                coordenadas = obj_json[obj].LATITUD+','+obj_json[obj].LONGITUD;
                cliente = obj_json[obj].PK_CLIENTE;
                estatus = obj_json[obj].PK_ESTATUS;
                repartidor = obj_json[obj].REPARTIDOR;
                direccion = obj_json[obj].DIRECCION;


                addMarker_Repartidor(pedido, coordenadas, cliente, repartidor, estatus,direccion);
              
              }

            },
            error: function() 
            {
              //alert("Hubo un error");
            } 	        
});

}



function initialize() {

  var myLatlng = new google.maps.LatLng(INIT_LAT, INIT_LONG);

  var mapOptions = {
      zoom: 14,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  map = new google.maps.Map(document.getElementById('map'), mapOptions);

  geocoder = new google.maps.Geocoder;

  directionsService = new google.maps.DirectionsService();

  infowindow = new google.maps.InfoWindow({
      disableAutoPan: true
  });

  function GetLocation() {

      // Try HTML5 geolocation.
      if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function (position) {
              var pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
              };

              //infoWindow.setPosition(pos);
              //infoWindow.setContent('Location found.');
              //infoWindow.open(map);
              map.setCenter(pos);
          }, function () {
              handleLocationError(true, infoWindow, map.getCenter());
          });
      } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
      }

  }


  function handleLocationError(browserHasGeolocation, infoWindow, pos) {
      /*infoWindow.setPosition(pos);
      infoWindow.setContent(browserHasGeolocation ?
                            'Error: The Geolocation service failed.' :
                            'Error: Your browser doesn\'t support geolocation.');
      infoWindow.open(map);*/
  }


}



//https://i.ibb.co/SKtD3Dj/repartidorblue.png


//------MARCADOR REPARTIDOR--------//

function addMarker_Repartidor(pedido, coordenadas, cliente, repartidor, estatus,direccion) {

  
    var repartidor_item = new RepartidorModel();
  
   
    var coord = coordenadas.split(",");
  
    var latCord = parseFloat(coord[0]);
    var lngCord = parseFloat(coord[1]);
  
    var posicionCiudadano = new google.maps.LatLng(latCord, lngCord);
  
    
  
    // 1 Nuevo Verde
    var url_imagen ="https://i.ibb.co/SKtD3Dj/repartidorblue.png";
    
    
  /*
    if (pk_status == "2") {
      //Preparando Naranja
        url_imagen = "https://i.ibb.co/gM6RvRY/pinnaranja.png";
        estado = '<span class="badge badge-secondary">Preparando</span>';
        
  
    } else if (pk_status == "3") {
        //Recogiendo Amarillo
        url_imagen = "https://i.ibb.co/GtqVtfL/pinamarillo.png";
        estado = '<span class="badge badge-warning">Recogiendo</span>';
  
    }else if (pk_status == 4) {
       //Entregando Morado
        url_imagen = "https://i.ibb.co/LRvK8P9/pinmorado.png";
        estado = '<span class="badge badge-info">Entregando</span>';
  
    }else if (pk_status == 5) {
      //Entregado Azul
       url_imagen = "https://i.ibb.co/YjNf1Rt/pinazul.png";
       estado = '<span class="badge badge-primary">Entregado</span>';
  
   }else if (pk_status == 6) {
    //Cancelado  //Rojo
     url_imagen = "https://i.ibb.co/VSDCHZk/pinrojo.png";
     estado = '<span class="badge badge-danger">Cancelado</span>';
  
  }*/
  
  
  
    var contentString = '<div style="width:350px; height:30px;"><p>' +
        '<b><a href="pedidos/detalle.php?id='+pedido+'" target="_blank">' + pedido + '</b><br/>' +
        '<span id="C_DIRECCION_' + direccion + '"></span><br/></p><div>';
  
  
    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });
  
  
    repartidor_item.Marker.setPositionNotAnimated(posicionCiudadano);
    repartidor_item.Marker.setIcon(url_imagen + pedido);
    repartidor_item.ImagenUrl = url_imagen+"#";
    repartidor_item.Marker.setMap(map);
    repartidor_item.Marker.title = pedido;
    repartidor_item.Marker.setLabel(pedido);
    repartidor_item.phone = pedido;
    repartidor_item.Nombre = repartidor;
    //ciudadano_item.Folio = folio;
    repartidor_item.ContentString = contentString;
    repartidor_item.Infowindow = infowindow;
    
    pedido = 'r'+pedido;

    REPARTIDORES_ARRAY[pedido] = repartidor_item;
  
    google.maps.event.addListener(repartidor_item.Marker, 'click', function () {
        //GET_IMAGE_CIUDADANO(usuario);
        //geocodeLatLng(usuario);
        repartidor_item.Infowindow.open(map, repartidor_item.Marker);
        //clickaqui
        //transmision(folio,usuario);
    });
  
    //$.playSound(URL_MEDIA + "demonstrative.mp3");
  
    //var titulo = nombre + "ALERTA";
    //showNotificationCIUDADANO(usuario, titulo);
  
  }


function addMarker_Ciudadano(pedido, coordenadas, cliente, repartidor, pk_status,tienda,imagen_tienda,direccion) {


var direccion = direccion;
var tienda = tienda;
var imagen_tienda = imagen_tienda;

  
  var ciudadano_item = new ClienteModel();

 
  var coord = coordenadas.split(",");

  var latCord = parseFloat(coord[0]);
  var lngCord = parseFloat(coord[1]);

  var posicionCiudadano = new google.maps.LatLng(latCord, lngCord);

  

  // 1 Nuevo Verde
  var url_imagen ="https://i.ibb.co/njR9rWh/pinverde.png";
  var estado = '<span class="badge badge-success">Nuevo</span>';
  

  if (pk_status == "2") {
    //Preparando Naranja
      url_imagen = "https://i.ibb.co/gM6RvRY/pinnaranja.png";
      estado = '<span class="badge badge-secondary">Preparando</span>';
      

  } else if (pk_status == "3") {
      //Recogiendo Amarillo
      url_imagen = "https://i.ibb.co/GtqVtfL/pinamarillo.png";
      estado = '<span class="badge badge-warning">Recogiendo</span>';

  }else if (pk_status == 4) {
     //Entregando Morado
      url_imagen = "https://i.ibb.co/LRvK8P9/pinmorado.png";
      estado = '<span class="badge badge-info">Entregando</span>';

  }else if (pk_status == 5) {
    //Entregado Azul
     url_imagen = "https://i.ibb.co/YjNf1Rt/pinazul.png";
     estado = '<span class="badge badge-primary">Entregado</span>';

 }else if (pk_status == 6) {
  //Cancelado  //Rojo
   url_imagen = "https://i.ibb.co/VSDCHZk/pinrojo.png";
   estado = '<span class="badge badge-danger">Cancelado</span>';

}




var button = '<a href="javascript:setPositionMarker('+pedido+')" class="list-group-item list-group-item-action online">';
button += '<img src="'+imagen_tienda+'" class="d-block ui-w-40 rounded-circle" alt=""><div class="media-body ml-3">';
button += '<small>'+tienda+'</small>';
button += '<div class="">';
button += estado+'</div>';
button += '</div>';
button += '<div class="badge badge-outline-dark">'+pedido+'</div>';
button += '</a>';

$("#pedidos").append(button);


var contentString = '<div><p>' +
'<b><a href="pedidos/detalle.php?id='+pedido+'" target="_blank">' + pedido + '</b><br/><br/>' +
'<span>' + direccion + '</span><br/></p><div>';


  var infowindow = new google.maps.InfoWindow({
      content: contentString
  });


  ciudadano_item.Marker.setPositionNotAnimated(posicionCiudadano);
  ciudadano_item.Marker.setIcon(url_imagen + pedido);
  ciudadano_item.ImagenUrl = url_imagen+"#";
  ciudadano_item.Marker.setMap(map);
  ciudadano_item.Marker.title = pedido;
  ciudadano_item.Marker.setLabel(pedido);
  ciudadano_item.phone = pedido;
  ciudadano_item.Nombre = tienda;
  //ciudadano_item.Folio = folio;
  ciudadano_item.ContentString = contentString;
  ciudadano_item.Infowindow = infowindow;
  

  CLIENTES_ARRAY[pedido] = ciudadano_item;

  google.maps.event.addListener(ciudadano_item.Marker, 'click', function () {
      //GET_IMAGE_CIUDADANO(usuario);
      //geocodeLatLng(usuario);
      ciudadano_item.Infowindow.open(map, ciudadano_item.Marker);
      //clickaqui
      //transmision(folio,usuario);
  });

  //$.playSound(URL_MEDIA + "demonstrative.mp3");

  //var titulo = nombre + "ALERTA";
  //showNotificationCIUDADANO(usuario, titulo);

}


class RepartidorModel{
  constructor() {
      var myLatlng = new google.maps.LatLng(0, 0);
      var image = {
          url: "https://i.ibb.co/SKtD3Dj/repartidorblue.png#",
          labelOrigin: new google.maps.Point(30, -10)
      };
      this.ImagenUrl = "";
      this.RotacionAnterior = 0;
      this.Rumbo = 0;
      this.Usuario = "";
      this.Nombre = "";
      this.ContentString="";
      this.Infowindow = "";
      this.Estatus = "";
      this.Ruta = "";
      this.Marker = new SlidingMarker({
          position: myLatlng,
          optimized: false,
          draggable: true,
          icon: image,    
          title: "",
          label: {
              text: "",
              color: "black",
          }
      });
  }
}



class ClienteModel {

  constructor() {
      var myLatlng = new google.maps.LatLng(0, 0);
      var icon_ciudadano = {
          url: "https://i.ibb.co/df4SbbX/marker-red.png#",
          labelOrigin: new google.maps.Point(30, -10)
      };
      this.ImagenUrl = "";
      this.Nombre = "";
      this.Folio = "";
      this.ContentString="";
      this.Infowindow = "";
      this.Estatus = "";
      this.Marker = new SlidingMarker({
          position: myLatlng,
          icon: icon_ciudadano,
          draggable: false,
          label: {
              text: "",
              color: "red"
          },
          title: "",
          phone: ""
      });
  }
}


function setPositionMarker(pedido){
  map.setCenter(CLIENTES_ARRAY[pedido].Marker.position);
  map.setZoom(19);
}



function clearMarkers(){
 
  for (var obj in CLIENTES_ARRAY) {
    //console.log(obj.toString());
    //console.log(CLIENTES_ARRAY[obj.toString()].Marker);
    CLIENTES_ARRAY[obj.toString()].Marker.setMap(null);
  }

  CLIENTES_ARRAY=[];

}

function clearMarkersRepartidores(){

  for (var obj in REPARTIDORES_ARRAY) {
    //console.log(obj.toString());
    //console.log(REPARTIDORES_ARRAY[obj.toString()].Marker);
    REPARTIDORES_ARRAY[obj.toString()].Marker.setMap(null);
  }

  REPARTIDORES_ARRAY=[];

  
}


/*********REVISAR SI EXISTEN NUEVOS PEDIDOS***********/
function checkNuevosPedidos() {
  //console.log('entra');
  getPedidos('1,2,3,4,5');
  getRepartidores();
  
}
