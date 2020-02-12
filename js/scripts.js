$(document).ready(function() {
  $( function() {
    $( "#tabs" ).tabs();
  } );

  $( ".accordion" ).accordion({
       active: false,
       collapsible: true,
       heightStyle: "content"
    });

    $( "input,textarea" ).focus(function() {
      $('.contact').removeClass("open");
      var parent = $(this).parent('span');
      $(parent).parent('.contact').addClass("open");
    });

    $(document).mouseup(function(e){
      var container = $("input,textarea");
      if (!container.is(e.target) && container.has(e.target).length === 0)
      {
          $('.contact').removeClass("open");
      }
    });

    $(".cats p").click(function(){
      if ( $('ul.categorie').hasClass("open") ){
    		$("ul.categorie").removeClass("open");
    	}
      else{
        $("ul.categorie").addClass("open");
      }
    });

    $("#search input").on('keyup', function (e) {
        if (e.keyCode === 13) {
            $("#search button").trigger('click');
        }
    });

});
