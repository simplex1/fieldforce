type = ['','info','success','warning','danger'];
    	

demo = {
    initPickColor: function(){
        $('.pick-class-label').click(function(){
            var new_class = $(this).attr('new-class');  
            var old_class = $('#display-buttons').attr('data-class');
            var display_div = $('#display-buttons');
            if(display_div.length) {
            var display_buttons = display_div.find('.btn');
            display_buttons.removeClass(old_class);
            display_buttons.addClass(new_class);
            display_div.attr('data-class', new_class);
            }
        });
    },
    
    initChartist: function(){    
        
        var dataSales = {
          labels: ['7:00AM', '8:00AM', '9:00AM', '10:00AM', '11:00AM', '12:00PM', '1:00PM', '2:00PM'],
          series: chartSeries.task_completion
        };
        
        var optionsSales = {
          lineSmooth: false,
          low: 0,
          high: chartSeries.max_value,
          showArea: true,
          height: "245px",
          axisX: {
            showGrid: false,
          },
          lineSmooth: Chartist.Interpolation.simple({
            divisor: 3
          }),
          showLine: false,
          showPoint: false,
        };
        
        var responsiveSales = [
          ['screen and (max-width: 640px)', {
            axisX: {
              labelInterpolationFnc: function (value) {
                return value[0];
              }
            }
          }]
        ];
    
        Chartist.Line('#chartHours', dataSales, optionsSales, responsiveSales);
        
    
        var data = {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          series: salesData.sales_volume/*[
            [542, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [412, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [650, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
          ] */
        };
        
        var options = {
            seriesBarDistance: 10,
            axisX: {
                showGrid: false
            },
            height: "245px"
        };
        
        var responsiveOptions = [
          ['screen and (max-width: 640px)', {
            seriesBarDistance: 5,
            axisX: {
              labelInterpolationFnc: function (value) {
                return value[0];
              }
            }
          }]
        ];
        
        Chartist.Bar('#chartActivity', data, options, responsiveOptions);
    
        var dataPreferences = {
            series: [
                [25, 30, 20, 25]
            ]
        };
        
        var optionsPreferences = {
            donut: true,
            donutWidth: 40,
            startAngle: 0,
            total: 100,
            showLabel: false,
            axisX: {
                showGrid: false
            }
        };
    
        Chartist.Pie('#chartPreferences', dataPreferences, optionsPreferences);
        
        Chartist.Pie('#chartPreferences', {
          labels: pieChartInfo.outlet_cnt_pct,
          series: pieChartInfo.outlet_cnt
        });   
    },
    
    initGoogleMaps: function(){
        var myLatlng = new google.maps.LatLng(6.453135, 3.395829);
        var mapOptions = {
          zoom: 13,
          center: myLatlng,
          scrollwheel: false, //we disable de scroll over the map, it is a really annoing when you scroll through page
          styles: [{"featureType":"water","stylers":[{"saturation":43},{"lightness":-11},{"hue":"#0088ff"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":99}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#808080"},{"lightness":54}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ece2d9"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#ccdca1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#767676"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#b8cb93"}]},{"featureType":"poi.park","stylers":[{"visibility":"on"}]},{"featureType":"poi.sports_complex","stylers":[{"visibility":"on"}]},{"featureType":"poi.medical","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","stylers":[{"visibility":"simplified"}]}]
    
        }
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);               
        
        coords.customers.forEach(function(row){
        var latlng = new google.maps.LatLng(row.latitude,row.longitude);
          var marker = new google.maps.Marker({
            position: latlng,
            title: row.outlet_name,
            draggable:true
          });                
          marker.setMap(map);  
          marker.addListener('click', function() { 
           location.href = "customerEdit.php?search_qty=1&search_param="+row.customer_id;   
          });        
        });                        
   
    },
    
    depotGoogleMaps: function(){
        var myLatlng = new google.maps.LatLng(6.453135, 3.395829);
        var mapOptions = {
          zoom: 13,
          center: myLatlng,
          scrollwheel: false, //we disable de scroll over the map, it is a really annoing when you scroll through page
          styles: [{"featureType":"water","stylers":[{"saturation":43},{"lightness":-11},{"hue":"#0088ff"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":99}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#808080"},{"lightness":54}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ece2d9"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#ccdca1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#767676"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#b8cb93"}]},{"featureType":"poi.park","stylers":[{"visibility":"on"}]},{"featureType":"poi.sports_complex","stylers":[{"visibility":"on"}]},{"featureType":"poi.medical","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","stylers":[{"visibility":"simplified"}]}]
    
        }
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);                       
        
        coords.employees.forEach(function(row){
        var latlng = new google.maps.LatLng(row.latitude,row.longitude);
          var marker = new google.maps.Marker({
            position: latlng,
            draggable:true,
            title: row.first_name+' '+row.last_name,
            icon: coords.img_path+'/assets/img/person_img.png'
          });                
          marker.setMap(map);  
          marker.addListener('click', function() { 
           location.href = "employeeEdit.php?search_qty=1&search_param="+row.employee_id;   
          });        
        });                        
   
    },
    
	showNotification: function(from, align){
    	color = Math.floor((Math.random() * 4) + 1);
    	
    	$.notify({
        	icon: "pe-7s-gift",
        	message: "Welcome to <b>Field Force</b> - Sales Management Solution."
        	
        },{
            type: type[color],
            timer: 4000,
            placement: {
                from: from,
                align: align
            }
        });
	},
  
  showMessageBox: function (msg,msgType){
  $.notify({
            	icon: 'pe-7s-gift',
            	message: msg
            	
            },{
                type: msgType,
                timer: 4000,
                placement: {
                  from: 'top',
                  align: 'center'
                }
            });        
  },
  
  loadRepBinPage: function(){
    location.href = "sales_rep_bin.php?emp_id="+$("select#employee_id").val();
  },
  
  updateRepBin: function(bin_id,emp_id){     
     var row = $("tr#"+bin_id);
     var employee_id = emp_id;
     var employee_bin_id = row.find('td input#employee_bin_id').val();
     var product_id = row.find('td select#product_id').val();   
     var product_uom_id = row.find('td select#product_uom_id').val();  
     var qty = row.find('td input#quantity').val();
     var msg="";
     var inf = "employee_id="+employee_id+"&employee_bin_id="+employee_bin_id+"&product_id="+product_id+"&product_uom_id="+product_uom_id+"&qty="+qty;    
    $.post( "api/Employee.php?job=updateEmployeeBin",inf, function(data) {
    var dat = JSON.parse(data);  
            if(dat.result > 0){
                msg = "Employee Bin updated!";         
                demo.showMessageBox(msg,'success');                     
            } else {
            msg = "Employee Bin NOT updated!"; 
            demo.showMessageBox(msg,'danger');                 
            }            
        }); 
  },
  
  startDay: function(){
  var msg="";
    $.post( "api/StartDay.php", function(data) {
    var dat = JSON.parse(data);  
            if(dat.result > 0){
                msg = "Day Started!";         
                demo.showMessageBox(msg,'success');                     
            } else {
            msg = "Day already Started!"; 
            demo.showMessageBox(msg,'danger');                 
            }            
        });
  }

    
}

