//global variables
var g_page;
var g_state;
var g_categories;
var g_sort;

//Set up the page: Initialize global variables, generate the table, set up the sorting function
function setup(pagenumber, state, categories,sort){
        g_page=pagenumber;
        g_state=state;
        g_categories=categories;
        g_sort=sort;
        new_frame();
        $('th').click(function (){ //Clicking the table headers sorts the rows
                 sort_table($(this));
         });
}

function sort_table(header){
          if(g_sort == header.html()){ //If we are already sorting according to this header, make the sort descending
                     g_sort = header.html()+" DESC";
          }
          else{
                      g_sort=header.html(); //Sort according to the header
          }
          new_frame(); //refresh the table
}

function new_frame(){
	$.post("table.php", //Get the data from the database
		   {"state":g_state, //Arguments to the table generator
			"categories":g_categories,
			"sort":g_sort,
			"page":g_page},
                   function (data){
			   if(data.nolaws){ //If there are no laws found, display an error message
				   $('#laws').replaceWith("<p class='errormessage'>Sorry, we don't have any laws with these specifications</p>");
                                   $("#footer").hide();
			   }
                           else{
		                  $('#laws > tbody:last').html(data.html);
                                  if(g_page==1){
                                            $("#previous").hide(); //If it's the first page, hide the previous button
                                  }
                                  else{
                                            $("#previous").show();//Make sure the previous button is displayed, hide the previous button
                                  }
                                  if(data.lastpage){
                                            $("#next").hide();//If it's the last page, hide the next button
                                  }
                                  else{
                                            $("#next").show();//Make sure the previous button is displayed
                                  }
                                  $("#newPageNumber").val(""+g_page); //Display the page number
                           }
                   }
       );
}
function changepage(pageNumber){ //Enable pagination
g_page=pageNumber;
var newrl = (document.URL).replace(/page=\d+&?/,'');
window.history.pushState({},'',newrl.replace(/laws.php\??/,"laws.php?page="+g_page+"&"));
new_frame();
$('body').scrollTop(0);
}
function gotopage(){//Go to page indicated in the goto form
changepage(parseInt($("#newPageNumber").val()));
}
function gotopreviouspage(){
changepage(g_page-1);
}
function gotonextpage(){
changepage(g_page+1);
}