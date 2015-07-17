$(function() {
    var $_info = $("<div class=info></div>");
    $( "#accordion" ).accordion({
        heightStyle: "content",
        event: "click hoverintent",
    });

    $( "#accordion2" ).accordion({
        heightStyle: "content",
        event: "click hoverintent",
    });

    $("input[type=radio]").click(function(e) {
        $("#accordion").hide();
        $("#accordion2").hide();
        $("#" + $(this).attr("for")).show();
    }); 

    $("a#alert").click(function(e) {
        alert("注意：上传的package请在命名上包含版本号 !!!");
        e.preventDefault();
    });

    $("a[name=path]").hover(function(e) {
        $_info.html($(this).attr("href"));
        $_info.insertAfter($(this).parent());
        e.preventDefault();
    });

    $("a[name=path]").click(function(e) {
        $_info.html($(this).attr("href"));
        $_info.insertAfter($(this).parent());
        e.preventDefault();
    });
});

$.event.special.hoverintent = {
    setup: function() {
      $( this ).bind( "mouseover", jQuery.event.special.hoverintent.handler );
    },
    teardown: function() {
      $( this ).unbind( "mouseover", jQuery.event.special.hoverintent.handler );
    },
    handler: function( event ) {
      var currentX, currentY, timeout,
        args = arguments,
        target = $( event.target ),
        previousX = event.pageX,
        previousY = event.pageY;
 
      function track( event ) {
        currentX = event.pageX;
        currentY = event.pageY;
      };
 
      function clear() {
        target
          .unbind( "mousemove", track )
          .unbind( "mouseout", clear );
        clearTimeout( timeout );
      }
 
      function handler() {
        var prop,
          orig = event;
 
        if ( ( Math.abs( previousX - currentX ) +
            Math.abs( previousY - currentY ) ) < 7 ) {
          clear();
 
          event = $.Event( "hoverintent" );
          for ( prop in orig ) {
            if ( !( prop in event ) ) {
              event[ prop ] = orig[ prop ];
            }
          }
          // Prevent accessing the original event since the new event
          // is fired asynchronously and the old event is no longer
          // usable (#6028)
          delete event.originalEvent;
 
          target.trigger( event );
        } else {
          previousX = currentX;
          previousY = currentY;
          timeout = setTimeout( handler, 100 );
        }
      }
 
      timeout = setTimeout( handler, 100 );
      target.bind({
        mousemove: track,
        mouseout: clear
      });
    }
};
