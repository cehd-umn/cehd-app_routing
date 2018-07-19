        // Load Search Function
        $("#full-text-search-input").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#FilterContainer div").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                newResults = $("div.events-container > div.event-card:visible").length;
            });
        }); // End of Searcb Function Load #}

        
        function todayFilter(value) {
            // console.log("Today Filter!");
            var eventsList = FilterContainer.querySelectorAll(".events");
            eventsList.forEach(function (event) {
                var date = event.getElementsByClassName("startdate")[0].innerText;
                var eventDate = moment(date).format('MM/DD/YYYY');
                var currentDate = moment().format('MM/DD/YYYY');
                // console.log('Current Date: ' + currentDate + '& Event Date: ' + eventDate);

                if (currentDate == eventDate) {
                    // console.log('YEP!');      
                    $(event).show(1000);
                } 
                else {
                    // console.log('NOPE!'); 
                    $(event).hide(0);

                }
            });

        };

        function weekFilter(value) {
            // console.log("Week Filter!");
            var eventsList = FilterContainer.querySelectorAll(".events");
            eventsList.forEach(function (event) {
                var date = event.getElementsByClassName("startdate")[0].innerText;
                var eventDate = moment(date).dayOfYear();
                var weekDate = moment().add(7, 'days').format('MM/DD/YYYY');
                var weekday= moment(weekDate).dayOfYear();
                // console.log('Week Date: ' + weekday + '& Event Date: ' + eventDate);

                if (weekday > eventDate) {
                    // console.log('YEP!');      
                    $(event).show(1000);
                } 
                else {
                    // console.log('NOPE!'); 
                    $(event).hide(0);
                }
                
            });
                };

        function monthFilter() {
            // console.log("Month Filter!")
            var eventsList = FilterContainer.querySelectorAll(".events");
            eventsList.forEach(function (event) {
                var date = event.getElementsByClassName("startdate")[0].innerText;
                var eventDate = moment(date).dayOfYear();
                var monthDate = moment().add(30, 'days').format('MM/DD/YYYY');
                var monthday= moment(monthDate).dayOfYear();
                // console.log('Month Date: ' + monthday + '& Event Date: ' + eventDate);
                // console.log('Month Date: ' + monthday + '& Event Date: ' + eventDate);
                if (monthday > eventDate) {
                    // console.log('YEP!');      
                    $(event).show(1000);
                } 
                else {
                    // console.log('NOPE!');
                    $(event).hide();
                }
            });
                };

        function clearFilter() {
            // console.log("Clear Filter!");
            $(".audiencefilter").css("color", "#333");
            $("div#FilterContainer").find("div").show(1000);
        };


        $('#audienceFilter li').click(function(){
            var $this = $(this);  
            $(".audiencefilter").css("font-weight", "500");
            this.style.fontWeight = "900";    
            var thiscode = $this.attr("value");              
        // console.log("Audience Filter!");
        // console.log($this);
        
        var newResults = $("div#FilterContainer").find("div." + thiscode).length;
        // console.log(newResults);
        $("div#FilterContainer").find("div").hide(0);
        $("div#FilterContainer").find("div." + thiscode).show(1000);
    })