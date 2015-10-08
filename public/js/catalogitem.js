// https://developers.google.com/maps/documentation/javascript/examples/geocoding-simple?hl=es
var geocoder;
var map;
var marker;

$(document).ready(function() {
    var $ratFul = '/public/images/rating/rating+full.png';
    var $ratNul = '/public/images/rating/rating.png';
    var uslg_val = 0, pers_val = 0, otnosh_val = 0;

    $(".uslovj").mousemove(function(){
        if (uslg_val === 0) {
            str_id = this.id;
            str_f_id = "";
            for (var i = 1; i <= 5; i++) {
                str_f_id="uslovj-"+i;
                $("#"+str_f_id).attr('src', $ratFul);
                if (str_f_id === str_id) {
                    break;                    
                }
            }
        }
    });

    $(".uslovj").mouseout(function(){
        if (uslg_val === 0) {
            for (var i = 1; i <= 5; i++) {
                $("#uslovj-"+i).attr('src', $ratNul);
            }
        }
    });

    $(".uslovj").click(function(){
        uslg_val = parseInt(this.id.replace('uslovj-',''));
        $("#uslovjEdt").val(uslg_val);
        
        for (var i = 1; i <= 5; i++) {
            if (i <= uslg_val) {
                $("#uslovj-"+i).attr('src', $ratFul);
            } else {
                $("#uslovj-"+i).attr('src', $ratNul);
            }
        }
    });
    
    
    $(".personal").mousemove(function(){
        if (pers_val === 0) {
            str_id = this.id;
            str_f_id = "";
            for (var i = 1; i <= 5; i++) {
                str_f_id="personal-"+i;
                $("#"+str_f_id).attr('src', $ratFul);
                if (str_f_id === str_id) {
                    break;                    
                }
            }
        }
    });

    $(".personal").mouseout(function(){
        if (pers_val === 0) {
            for (var i = 1; i <= 5; i++) {
                $("#personal-"+i).attr('src', $ratNul);
            }
        }
    });

    $(".personal").click(function(){
        pers_val = parseInt(this.id.replace('personal-',''));
        $("#personalEdt").val(pers_val);
        
        for (var i = 1; i <= 5; i++) {
            if (i <= pers_val) {
                $("#personal-"+i).attr('src', $ratFul);
            } else {
                $("#personal-"+i).attr('src', $ratNul);
            }
        }
    });
    
    
    $(".uvaga").mousemove(function(){
        if (otnosh_val === 0) {
            str_id = this.id;
            str_f_id = "";
            for (var i = 1; i <= 5; i++) {
                str_f_id="uvaga-"+i;
                $("#"+str_f_id).attr('src', $ratFul);
                if (str_f_id === str_id) {
                    break;                    
                }
            }
        }
    });

    $(".uvaga").mouseout(function(){
        if (otnosh_val === 0) {
            for (var i = 1; i <= 5; i++) {
                $("#uvaga-"+i).attr('src', $ratNul);
            }
        }
    });

    $(".uvaga").click(function(){
        otnosh_val = parseInt(this.id.replace('uvaga-',''));
        $("#uvagaEdt").val(otnosh_val);
        
        for (var i = 1; i <= 5; i++) {
            if (i <= otnosh_val) {
                $("#uvaga-"+i).attr('src', $ratFul);
            } else {
                $("#uvaga-"+i).attr('src', $ratNul);
            }
        }
    });
});

$(function () {
    /* Табы */
    //$('.tabs').children('.tab_item').eq(0).show().css({"opacity" : "1"});

    $('.tab_line').on('click', '.tab', function() {
        tempIndex = $(this).siblings('.bl, .pur, .gr, .yel, .bl').index();
        tempIndex2 = $(this).index();
        if(tempIndex == 0) {
            $(this).siblings('.bl, .pur, .gr, .yel, .bl').css({"z-index" : "6"});
        }
        else if(tempIndex == 1) {
            $(this).siblings('.bl, .pur, .gr, .yel, .bl').css({"z-index" : "5"});
        }
        else if(tempIndex == 2) {
            $(this).siblings('.bl, .pur, .gr, .yel, .bl').css({"z-index" : "4"});
        }
        else if(tempIndex == 3) {
            $(this).siblings('.bl, .pur, .gr, .yel, .bl').css({"z-index" : "3"});
        }
        else if(tempIndex == 4) {
            $(this).siblings('.bl, .pur, .gr, .yel, .bl').css({"z-index" : "2"});
        }
        else if(tempIndex == 5) {
            $(this).siblings('.bl, .pur, .gr, .yel, .bl').css({"z-index" : "1"});
        }
        $(this).siblings('.bl, .pur, .gr, .yel, .bl').removeClass().addClass('tab');
        $(this).css({"z-index" : "10"});
        if(tempIndex2 == 0) {
            $(this).addClass('pur');
        }
        else if(tempIndex2 == 1) {
            $(this).addClass('bl');
        }
        else if(tempIndex2 == 2) {
            $(this).addClass('yel');
        }
        else if(tempIndex2 == 3) {
            $(this).addClass('gr');
        }
        else if(tempIndex2 == 4) {
            $(this).addClass('pur');
        }
        else if(tempIndex2 == 5) {
            $(this).addClass('bl');
        }
        temp = $(this).parent('.tab_line').siblings('.tabs').children('.tab_item').eq($(this).index());
        $(this).parent('.tab_line').siblings('.tabs').children('.tab_item:visible').animate({opacity:0}, 300, function() {
            $(this).hide();
            temp.show().animate({opacity:1}, 300);
        });
        
        if (this.id === 'MapBox') {
            timeoutId = setTimeout(initialize_map, 350);        
        }
    });
    
    function initialize_map() {
      if (geocoder) {
          return; // if already exists
      }
        
      geocoder = new google.maps.Geocoder();
      
      var vLat = $("#MapXEdt").val();
      var vLng = $("#MapYEdt").val();
      var vZoom = 16;
      var vMakeMarker = true;
      var vLocated = false;
      
      if ((vLat === "") || (vLng === "")) {
        var address = $("#FullAddressEdt").val();
        address = address.replace("пгт.", "");
        
        if (address !== "") {
            geocoder.geocode( { 'address': address}, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                map.setZoom(16);
                placeMarker(results[0].geometry.location);
                vLocated = true;
              }
            });
        }
      }
      
      if (vLocated) {
          return;
      }
      
      if ((vLat === "") || (vLng === "")) {
        vLat = 49;
        vLng = 32;
        vZoom = 6;
        vMakeMarker = false;
      }
      
      var latlng = new google.maps.LatLng(vLat, vLng);
      var mapOptions = {
        zoom: vZoom,
        center: latlng
      };
      map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
      
      if (vMakeMarker === true) {
        placeMarker(latlng);
      }
    }

    function placeMarker(location) {
        if (marker) {
            //if marker already was created change positon
            marker.setPosition(location);
        } else {
            //create a marker
            marker = new google.maps.Marker({          
                position: location,
                map: map,
                draggable: true
            });
        }
    }    
});