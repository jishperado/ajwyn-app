// for pressing enter key
$('#searchInputBox').keypress(function(e){ $('#whichformbox').val('1');  });
$('#searchInputBoxmob').keypress(function(e){ $('#whichformbox').val('2');  });
$('#subscribemail').keypress(function(e){ $('#whichformbox').val('3');  });



document.onkeyup = function(e){
    if(e){
        var key = window.event ? e.keyCode : e.which;
    }else{
        var key = window.event ? event.keyCode : event.which;
    }
    if (key == '13') {
        
        var whichform = $('#whichformbox').val();
        if(whichform==1){ $('#topheaderSearchButton').trigger('click'); }
        if(whichform==2){ $('#topheaderSearchButtonmob').trigger('click'); }
        if(whichform==3){ $('.btn-subscribe').trigger('click'); }
    }
}
// for pressing enter key


/* search */


$('#topheaderSearchButton').click(function(e){
  var searchTerm = $('#searchInputBox').val();
  if(searchTerm !='')
  {
  var redirectTo = DOC_ROOT + "/" 
    + encodeURIComponent(searchTerm);

  window.location.href = redirectTo;  
  }

});


$('#topheaderSearchButtonmob').click(function(e){
  var searchTerm = $('#searchInputBoxmob').val();
  if(searchTerm !='')
  {
  var redirectTo = DOC_ROOT + "/" 
    + encodeURIComponent(searchTerm);

  window.location.href = redirectTo;  
  }

});



$('.page-item').click(function(e){
    $('html, body').animate({
        'scrollTop' : $("#searchBody").position().top
    });
});