<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<br>
<br>
<br>
<form action="" id="linkbuilder" class="col-lg-6 offset-lg-3 ">
    <div class="row justify-content-center form-inline ">
        <input type="text" name="link" placeholder="Напечатайте адрес" class="form-control mr-sm-2">
        <span class="input-group-btn">
        <input type="submit" value="Отправить" class="btn btn-primary">
        </span>
    </div>
</form>
<br>
<br>
<br>
<div class="justify-content-center row">
<input type="text" name="short" id="short" class="form-control mr-sm-2" style="max-width: 400px;"></div>
<script>
$(function() {
    $('#linkbuilder').on('submit', function(e) {
        e.preventDefault();
        $.post("build", $(this).serialize(),
            function(data) {
                $('#short').val(data);
            });
    })
});
</script>