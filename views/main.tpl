
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
    <div class="row">
        <div class="col-lg-8 offset-lg-2 labels">
    <label for=""><input type="checkbox" name="double" id="">Создать дублирующую короткую ссылку</label>    
    <label for=""><input type="checkbox" name="future" id="">Создать ссылку для будущего ресурса</label>
    </div>
    </div>
</form>
<br>
<br>
<br>

<div class="justify-content-center d-flex position-relative">
<div class="explain">Скопировано!</div><input type="text" name="short" id="short" class="form-control col-lg-6" style="max-width: 400px;"><div class="copy" title="Копировать ссылку"><img src="/static/img/copy.svg" alt="" width="40"></div></div>
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
<style>
    .explain{
        display: none;
        position: absolute;
        left: 45%;
        top: -30px;
        color: #38aa2e;
    }
    .labels{
        margin-top: 15px;
        font-size: 0.7em;
    }
    .labels input{
        margin-right: 10px;
    }
</style>