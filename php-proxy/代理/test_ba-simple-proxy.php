$(function(){
  
  // Handle form submit.
  $('#params').submit(function(){
    var proxy = '../../ba-simple-proxy.php',
      url = proxy + '?' + $('#params').serialize();
    
    // Update some stuff.
    $('#request').html( $('<a/>').attr( 'href', url ).text( url ) );
    $('#response').html( 'Loading...' );
    
    // Test to see if HTML mode.
    if ( /mode=native/.test( url ) ) {
      
      // Make GET request.
      $.get( url, function(data){
        
        $('#response')
          .html( '<pre class="brush:xml"/>' )
          .find( 'pre' )
            .text( data );
        
        SyntaxHighlighter.highlight();
      });
      
    } else {
      
      // Make JSON request.
      $.getJSON( url, function(data){
        
        $('#response')
          .html( '<pre class="brush:js"/>' )
          .find( 'pre' )
            .text( JSON.stringify( data, null, 2 ) );
        
        SyntaxHighlighter.highlight();
      });
    }
    
    // Prevent default form submit action.
    return false;
  });
  
  // Submit the form on page load if ?url= is passed into the example page.
  if ( $('#url').val() !== '' ) {
    $('#params').submit();
  }
  
  // Disable AJAX caching.
  $.ajaxSetup({ cache: false });
  
  // Disable dependent checkboxes as necessary.
  $('input:radio').click(function(){
    var that = $(this),
      c1 = 'dependent-' + that.attr('name'),
      c2 = c1 + '-' + that.val();
    
    that.closest('form')
      .find( '.' + c1 + ' input' )
        .attr( 'disabled', 'disabled' )
        .end()
      .find( '.' + c2 + ' input' )
        .removeAttr( 'disabled' );
  });
  
  // Clicking sample remote urls should populate the "Remote URL" box.
  $('#sample a').click(function(){
    $('#url').val( $(this).attr( 'href' ) );
    return false;
  });
});
