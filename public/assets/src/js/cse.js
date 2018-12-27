(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
(function ($) {
  //global variables
  var myAccountUrl = "http://myaccount.umn.edu/lookup?SET_INSTITUTION=UMNTC&type=name&campus=a&role=any&CN=";
  var deptUrl = "https://directory.umn.edu/search?q=";
  var peopleUrl = "https://myaccount.umn.edu/lookup?format=JSON&results=ALL&CN=";

  //the main search functions
  var search={

    get: {
      queryVariable: function (variable){
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++) {
                var pair = vars[i].split("=");
                if(pair[0] == variable){return pair[1];}
        }
        return(false);
      },

      query: function (){
        //shorter way to get the query
        var q = search.get.queryVariable("query");
        return q;
      },

      campus: function(){
        //get the campus location from the URL
        var x = window.location.href;
        var x = x.split('/').pop().split('?')[0];

        if(x==""){
          return "tc";
        }else if(x=="all-campuses"){
          return "all";
        }else{
          return x;
        }
      }
    },

    check:{
      space: function(){
        //check if the search query contains spaces
        var q = search.get.query();
        var s = '%20';
        if(q.indexOf(s) !== -1){
          return true;
        }else{
          return false;
        }
      },

      url: function(){
        //check the current page url
        var u = window.location.href;

        if(u.indexOf("search-results") > -1) {
           return true;
        }else{
          return false;
        }
      }
    },

    replace:{
      plus: function(){
        var s = /\%20/gi;
        var q = search.get.query();
        var q = q.replace(s, '+' );
        return q;
      }
    },

    click:{
      filter: function(){
        //define the links
        var $f = $('.search-menu__link');

        //what to do when clicked
        $f.click(function(){
          //don't do anything with links yet
          //e.preventDefault();
          //get the id of the link clicked
          var id = $(this).attr('id');

          //now do things depending on the id
          switch (id){
            case 'search-people':
              $(this).attr('href', myAccountUrl + search.replace.plus());
              break;

            case 'search-depts':
              $(this).attr('href', deptUrl + search.replace.plus());
              break;

            case 'search-web':
              $(this).attr('href', window.location.href);
              break;
          }

        });
      },

      campusMatch:function(){
        var c = search.get.campus();
        console.log("campus = " + c);

        $("select#campus_select option").prop("selected", false); //deselect all
        $("select#campus_select option[value="+c+"]").prop("selected", true); //select only the matching option

      },

      campusSelect:function(){
       var $c = $("select#campus_select");

       $c.change(function() {
         //base url
         var w="";

         //search value
         var q = "?query=" + search.get.query();

         //get value of selection
         var linkID= $(this).find(':selected').val();

         switch (linkID){
           case 'all':
             //location = c_default;
             console.log("selected: all campuses");
             location = "/all-campuses" + q;
             break;
           case 'crookston':
             console.log("selected: crookston");
             location = "/" + linkID + q;
             break;
           case 'duluth':
             console.log("selected: duluth");
             location = "/" + linkID + q;
             break;
           case 'morris':
             console.log("selected: morris");
             location = "/" + linkID + q;
             break;
           case 'rochester':
             console.log("selected: rochester");
             location = "/" + linkID + q;
             break;
           case 'tc':
             console.log("selected: twin cities");
             location = "/" + q;
             break;
           case 'onestop':
             console.log("selected: onestop");
             location = "/" + linkID + q;
             break;
           }
       });
     }
    },

    //people search functions
    people:{
      //only need this if we're not able to include
      //the html in the template
      makeContainer:function(){
        var $main = $('.search-results-area');
        var $div = "<div class='people-search__container'><h2 id='um_srch_people_header' class='people-search__header h4'>People Search Results</h2><div class='people-search__list-container'></di></div>";
        var $m = $main.append($div);
      },

      callAPI:function(){
        var q = search.get.query();
    		var theUrl = peopleUrl+q;

        $.ajax({
    			url: theUrl,
    			type: "GET",
    			dataType: "json",
          async: false,
    			xhrFields: {
    				withCredentials: false
    			},
    		  success: function(response){
  			    $(".people-search__list-container, .people-search__svg-placeholder").empty();
  			    $(".people-search__svg-placeholder").toggleClass('active');
            console.log("JSON url = " + theUrl);

      			var peopleHtml = "<div role='list' id='people-search__items' class='people-search__results'>";

            if(response && response.people && response.people.length > 0){
      				var people = response.people;
              var total = "<div class='search-results__total'>Total results: " + response.people.length + "</div>";
              // peopleHtml += total;

      				for(var i = 0; i <= response.people.length; i++){
      					if(!$.isEmptyObject(people[i])){
                  console.log("is department blocking working?");
      						peopleHtml += "<div role='listitem' class='people-search__result'>" +
      							"<a class='people-search__name people-search__result-item' href='http://www.umn.edu/lookup?SET_INSTITUTION=UMNTC&amp;type=name&amp;campus=a&amp;role=any&amp;CN=" + people[i].uid  + "'>" +
      								people[i].freeFormName + " (" + people[i].persontypelist + ")</a>";

      						// reg unit
      						if((people[i].umnRegUnit.length > 0) && (people[i].persontypelist == 'student')){
      							peopleHtml += "<div class='people-search__reg-unit people-search__result-item'>" + people[i].umnRegUnit + "</div>";
      						}
      						// dept
      						if(people[i].ou.length > 0) {
      							peopleHtml += "<div class='people-search__dept people-search__result-item'>" + people[i].ou + "</div>";
      						}
      						//fax
      						// if(people[i].fax.length > 0) {
      						// 	peopleHtml += "<div class='umn_search_people_phone'>Fax: <a href='tel:" + people[i].fax + "'>" + people[i].fax + "</a></div>";
      						// }
      						// Home phone
      						// if(people[i].homephone.length > 0) {
      						// 	peopleHtml += "<div class='umn_search_people_phone'>Home: <a href='tel:" + people[i].homephone + "'>" + people[i].homephone + "</a></div>";
      						// }
      						// Mobile phone
      						// if(people[i].mobile.length > 0) {
      						// 	peopleHtml += "<div class='umn_search_people_phone'>Mobile: <a href='tel:" + people[i].mobile + "'>" + people[i].mobile + "</a></div>";
      						// }
      						// email
      						if(people[i].email.length > 0) {
      							peopleHtml += "<a class='people-search__email people-search__result-item' href='mailto:" + people[i].email + "'>" + people[i].email + "</a>";
      						}
      						// Work Phone
      						if(people[i].workphone.length > 0) {
      							peopleHtml += "<a class='people-search__tel people-search__result-item' href='tel:" + people[i].workphone + "'>" + people[i].workphone + "</a>";
      						}
      						// the very end
      						peopleHtml += "</div>";
      					}
      				}

        			} else {
        				peopleHtml += "<span> No people found.</span>";
        			}

      			  peopleHtml += "</div></div></div>";

      			if(response.people && response.people.length > 1){
      				peopleHtml += "<div id='um_srch_people_footer' style='margin-top:10px;'>" +
      	      			"<a href='https://www.umn.edu/lookup?SET_INSTITUTION=UMNTC&amp;type=name&amp;campus=a&amp;role=any&amp;CN=" + q + "'>View more People Search results" +
      					"</a>" +
      	   			 "</div>";
      			}

      			$(".people-search__list-container").append(peopleHtml);
    		  },

          error: function(err){
      			console.log(err);
      			$(".people-search__list-container").empty();
      			var peopleHtml = "<div role='list' id='people-search__items'>";
      			peopleHtml += "<span> No people found.</span></div>";
            $(".people-search__list-container").append(peopleHtml);
      		}
      });
    }
  },

    //run everything on init
    init: function(){
      //run campus select
      search.click.campusMatch();
      search.click.campusSelect();
      //run only if there's a search query
      if ( search.get.queryVariable("query") != false){
        search.click.filter();
        search.people.callAPI();
      } else {
        $(".people-search__container").empty();
      }
    }
  }

//run everything once the page loads
window.onload = function() {
  search.init();
};
}(jQuery));

},{}]},{},[1])
