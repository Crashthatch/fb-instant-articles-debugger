
$('.run').on('click', function(){
  $.post('transform.php', {
    "input-html": $('.html-input textarea').val(),
    "input-rules": $('.rules-input textarea').val()
  })
  .then( function(response){
    $('.html-output textarea').val(response.result);
    $('.log-output textarea').val(response.log);
  },
  function(err){
    console.error(err);
  });
});