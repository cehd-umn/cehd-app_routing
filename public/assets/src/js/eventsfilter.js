// Load Search Function
$("#full-text-search-input").on("keyup", function() {
  var value = $(this)
    .val()
    .toLowerCase();
  $("#FilterContainer div").filter(function() {
    $(this).toggle(
      $(this)
        .text()
        .toLowerCase()
        .indexOf(value) > -1
    );
    newResults = $("div.events-container > div.event-card:visible").length;
  });
}); // End of Searcb Function Load #}

function todayFilter(value) {
//   console.log("Today Filter!");
  clearDom()

  var eventsList = FilterContainer.querySelectorAll(".events");
  eventsList.forEach(function(event) {
    var date = event.getElementsByClassName("startdate")[0].innerText;
    var eventDate = moment(date).format("MM/DD/YYYY");
    var currentDate = moment().format("MM/DD/YYYY");
    console.log("Current Date: " + currentDate + "& Event Date: " + eventDate);

    if (currentDate == eventDate) {
      console.log("YEP!");
      $(event).show(1000);
      $(event).addClass("displayEvent");
    } else {
      console.log("NOPE!");
      $(event).hide(0);
    }
  });
  results = FilterContainer.querySelectorAll(".displayEvent").length;
  console.log(results);
  if (results == 0) {
    console.log("YEP!");
    var elem = document.getElementById("noEvents");
    elem.style.display = "block";
  }
}

function weekFilter(value) {
  // console.log("Week Filter!");
  clearDom()

  var eventsList = FilterContainer.querySelectorAll(".events");
  eventsList.forEach(function(event) {
    var date = event.getElementsByClassName("startdate")[0].innerText;
    // console.log(date);
    var weekDate = moment().add(7, "days");
    // console.log(weekDate);

    if (moment(date).isAfter(weekDate)) {
      // console.log('YEP!');
      $(event).hide(0);
    } else {
      // console.log('NOPE!');
      $(event).show(1000);
      $(event).addClass("displayEvent");
    }
  });
  results = FilterContainer.querySelectorAll(".displayEvent").length;
  // console.log(results);
  if (results == 0) {
    var elem = document.getElementById("noEvents");
    elem.style.display = "block";
  }
}

function monthFilter() {
  // console.log("Month Filter!")
  var elem = document.getElementById("noEvents");
  elem.style.display = "none";

  var eventsList = FilterContainer.querySelectorAll(".events");
  eventsList.forEach(function(event) {
    var date = event.getElementsByClassName("startdate")[0].innerText;
    // console.log(date);
    var monthDate = moment().add(30, "days");
    // console.log(monthDate);

    // var eventDate = moment(date).dayOfYear();
    // var weekDate = moment().add(7, 'days').format('MM/DD/YYYY');
    // var weekday= moment(weekDate).dayOfYear();
    // console.log('Week Date: ' + weekday + '& Event Date: ' + eventDate);

    // console.log(moment(date).isAfter(monthDate)); // returns false

    if (moment(date).isAfter(monthDate)) {
      // console.log('YEP!');
      $(event).hide(0);
    } else {
      // console.log('NOPE!');
      $(event).show(750);
      $(event).addClass("displayEvent");
    }
  });
  results = FilterContainer.querySelectorAll(".displayEvent").length;
  // console.log(results);
  if (results == 0) {
    var elem = document.getElementById("noEvents");
    elem.style.display = "block";
  }
}

function clearFilter() {
  // console.log("Clear Filter!");
  clearDom()
  $(".audiencefilter").css("font-weight", "500");
  $("div#FilterContainer")
    .find("div")
    .show(1000);
}

function clearDom() {
var noEvent = document.getElementById("noEvents");
noEvent.style.display = "none";
var clearEvents = document.querySelectorAll(".events.displayEvent");
[].forEach.call(clearEvents, function(el) {
    el.classList.remove("displayEvent");
    });
}

$("#audienceFilter li").click(function() {
  var $this = $(this);
  $(".audiencefilter").css("font-weight", "500");
  this.style.fontWeight = "900";
  var thiscode = $this.attr("value");
  // console.log("Audience Filter!");
  // console.log($this);

  var newResults = $("div#FilterContainer").find("div." + thiscode).length;
  // console.log(newResults);
  $("div#FilterContainer")
    .find("div")
    .hide(100);
  $("div#FilterContainer")
    .find("div." + thiscode)
    .show(1000);
});
