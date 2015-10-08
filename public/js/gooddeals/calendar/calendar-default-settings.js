('.b-calendar').zabuto_calendar({
      language: "ru",
       nav_icon: { 
        prev: '<i class="fa fa-chevron-circle-left"></i>', 
        next: '<i class="fa fa-chevron-circle-right"></i>' 
      },
      legend: [ 
        {
          type: "text", 
          label: "Special event", 
          badge: "00"
        }, 
        {
          type: "block", 
          label: "Regular event", 
          classname: "purple"
        }, 
        
        {
          type: "spacer"
        },

        {
          type: "text", 
          label: "Bad"
        },

        {
          type: "list", 
          list: ["grade-1", "grade-2", "grade-3"]
        },

        {
          type: "text", 
          label: "Good"
        }
      ],
      data: eventData
      /*ajax: { 
        url: "../show_data.php",
        modal: true
        }
      */
    });