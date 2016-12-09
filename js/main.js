
$('.run').on('click', function(){
  //Clear outputs.
  $('.html-output textarea').val('');
  $('.log-output textarea').val('');
  $('.rules-output textarea').val('');
  $('.instant-article-object textarea').val('');

  //Send to server.
  $.post('transform.php', {
    "input-html": $('.html-input textarea').val(),
    "input-rules": $('.rules-input textarea').val(),
    "include-wp-default-rules": $('#include-wp-default-rules').is(':checked')
  })
  .then( function(response){
    //Show results.
    $('.html-output textarea').val(response.result);
    $('.log-output textarea').val(response.log);
    $('.rules-output textarea').val(response.rules);
    $('.instant-article-object textarea').val(response['instant-article-object']);
  },
  function(err){
    console.error(err);
    if( err.responseJSON.error ){
      $('.log-output textarea').val("Error: "+err.responseJSON.error );
    }
  });
});