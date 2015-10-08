$(document).ready(function() {
  var  $document = $(document)
       $banner = $('.b-banner');
       //$calendarNav = $('.b-calendar__nav--prev');
  //console.log($calendarNav);
  function init() {
    $document.on('scroll', closeBanner);
    $document.on('resize', closeBanner);
    //$calendarNav.on('click', closeBanner);
  }

  function closeBanner(){
    $banner.hide();
    $('.b-calendar .calendar-dow td[id*="zabuto_calendar"]').removeClass("active");
  }

  init();

  function kp(e) { 
    if (e) {keyCode = e.which} else if (event) {keyCode=event.keyCode} else {return} 
    if (keyCode == 13 ) {
      $(".pixel-perfect").toggle();
    } 
  } 
  document.onkeypress = kp; 
  


  $(".b-owl-slider").owlCarousel({
    autoPlay : 3000,
    navigation : false, // Show next and prev buttons
    slideSpeed : 300,
    paginationSpeed : 400,
    singleItem: true,
    transitionStyle : "fade"
  });
  $('.owl-controls').remove();
  $('.b-sidebar-slider__body').ulslide({
      width: 249,
      height: 67,
      
      effect: {
        type: 'carousel', // slide or fade
        axis: 'y',        // x, y
        showCount: 4    // Distance between frames
      },
    nextButton: '.b-sidebar-slider__arrow-bottom',
    prevButton: '.b-sidebar-slider__arrow-top',  
    //autoslide: 2000,
    mousewheel: true,
    duration: 250
  });
  
  var eventData = [ 
      {"date":"2015-10-01","badge":false, "title":"Example 1","classname":"red banner-123 "}, 
      {"date":"2015-10-02","badge":false, "title":"Example 2", "classname":"yellow "},
      {"date":"2015-10-12","badge":false, "title":"Example 4", "classname":"yellow "},
      { 
        "date": "2015-10-04", 
        "badge": false, 
        "title":"Tonight",
        "classname":"purple" 
      }
        ];
  $('.b-calendar').zabuto_calendar({
      language: "ru",
       nav_icon: { 
        prev: '<div class="b-calendar__nav-container--prev"><div class="b-calendar__nav--prev"></div></div>',//'<i class="fa fa-chevron-circle-left"></i>', 
        next: '<div class="b-calendar__nav-container--next"><div class="b-calendar__nav--next"></div></div>'//'<i class="fa fa-chevron-circle-right"></i>' 
      },
      legend: [ 
        {
          type: "block", 
          label: "Мероприятия", 
          classname: "yellow"
        },
        {
          type: "block", 
          label: "Акции", 
          classname: "red"
        },
        {
          type: "block", 
          label: "Идем в гости", 
          classname: "purple"
        }

      ],
      action: function () {
        return activeClass(this.id);
      },
      action_nav: function () {
        return closeBanner();
      },
      data: eventData
      /*- тестовый код
      ajax: { 
        url: "../show_data.php",
        modal: true
        }
      */
    });
    
    // - тестовый код
    //var $bCalendarData = $('.b-calendar .calendar-dow td[id*="zabuto_calendar"]') 
    //console.log($bCalendarData); 

    function activeClass(id) {
      var $someBanner, // - Текущий баннер ? - как он будет подгружаться?
          $allBanner = $('.b-banner'),
          $hasClass = $('#'+id).closest('td').hasClass('event-styled');
      
      /* - тестовый код
      if(true) { 
        $someBanner = $('data-role[' + 'a' +']');
        console.log($someBanner);
      }*/

      //console.log($('#'+id)); - тестовый код
      $('.b-calendar .calendar-dow td[id*="zabuto_calendar"]').removeClass("active");
      $('#'+id).addClass("active");
      
      
      //console.log($hasClass); - тестовый код
      console.log($('.b-banner').is(':visible'));
      console.log( $('#'+id).hasClass());
      if($hasClass && !($('.b-banner').is(':visible'))){
        $allBanner.hide(); // все закрыть
        $('.b-banner').show(); // уникальный открыть
        console.log('true way');
      }else{
        $allBanner.hide(); 
        console.log('false way');
      }
    };
    function navCloseBanner(id) {
      console.log('hello');
      $banner.hide();
    }
});