<style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #data_map {height: 100%;}            		
    </style>


     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDroRyqNqyreJgApc95F-zuQ3BsKmM72QU&sensor=false" type="text/javascript"></script>     
    
    
    <script type="text/javascript">	  
    
  var map;
  var markersArray = [];
  var routePlan = [];
  var latlng;
  var myOptions;
  
  function setupMap(){
   latlng = new google.maps.LatLng(6.453135, 3.395829);  
   myOptions = {zoom: 11, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
   map = new google.maps.Map(document.getElementById("data_map"), myOptions); 
   map.panTo(latlng);  
  }    
  
  function addMarker(lat,lon, descrip,icon) { 
    var latlng = new google.maps.LatLng(lat,lon);      
    var marker = new google.maps.Marker({position: latlng, title:descrip,map: map,icon: icon,draggable:true});    
    markersArray.push(marker);
    routePlan.push(latlng);
    marker.setMap(map);     
   }        
  
  function initializeMap() {           
   setupMap();   
   coords.forEach(function(row){
    addMarker(row.lat,row.lon,row.descrip,row.icon);
   });
  
   var routePath = new google.maps.Polyline({path: routePlan,strokeColor: "#000000",strokeOpacity: 1.0,strokeWeight: 5});
   routePath.setMap(map);    
  }
  
  google.maps.event.addDomListener(window, 'load', initializeMap);    

  </script>