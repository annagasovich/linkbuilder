<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Сокращатель ссылок ВЦИОМ</title>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
</head>
<body>

	<header>
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand mr-auto" href="/">Сокращатель ссылок</a>
        <a class="navbar-brand" href="/admin/"><img src="/static/img/admin.svg" alt="" width="40"></a>
      </nav>
    </header>

<div class="container">
	{content}
</div>
</body>

<script>
$(function() {
	$('.copy').click(function(){
		/* Get the text field */
  var copyText = $(this).siblings('[type="text"]').first()[0];

  console.log(copyText);

  copyText.focus();
  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

  /* Copy the text inside the text field */
  document.execCommand("copy");

  /* Alert the copied text */
  $('.explain').show();
  setTimeout(function(){$('.explain').hide();}, 2000);
	});
});
</script>
<style>
	.copy{
		display: inline-block;
		opacity: 0.5;
		cursor: pointer;
	}
	.copy:hover{
		opacity: 0.8;
	}
</style>
</html>