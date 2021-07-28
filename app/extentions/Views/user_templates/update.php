<h1>Изменить пользователя</h1>
<form class="form-horizontal" method="post">
<fieldset>

<?php foreach ($fields as $field) { ?>
<div class="form-group">
	<?php if(isset($this->config['headers'][$field['Field']])) { ?>
	<label class="col-md-4 control-label" for="textinput"><?= $this->config['headers'][$field['Field']] ?></label>
<?php } else { ?>
	<label class="col-md-4 control-label" for="textinput"><?= ucfirst(str_replace('_',' ',$field['Field'])) ?></label>
<?php } ?>
  
  <div class="col-md-6">
<?php if($field['Type'] == 'date' || $field['Type'] == 'datetime') { ?>
    <input id="<?= $field['Field'] ?>" name="<?= $field['Field'] ?>" value="<?= $row[$field['Field']] ?>" type="date" placeholder="" class="form-control input-md" <?php if($field['Null']) echo 'required=""' ?>>
<?php } else if(stripos($field['Type'], 'int') === false) { ?>
    <input id="<?= $field['Field'] ?>" value="<?= $row[$field['Field']] ?>" name="<?= $field['Field'] ?>" type="text" placeholder="" class="form-control input-md" <?php if($field['Null']) echo 'required=""' ?>>
<?php } else { ?>
    <input id="<?= $field['Field'] ?>" value="<?= $row[$field['Field']] ?>" name="<?= $field['Field'] ?>" type="number" placeholder="" class="form-control input-md" <?php if($field['Null']) echo 'required=""' ?>>
<?php } ?>
</div>
</div>
<?php } ?>


    <div class="form-group">
        <label class="col-md-4 control-label" for="textinput">Пароль</label>

        <div class="col-md-6">
            <input id="username" value="" name="password" type="password" placeholder="" class="form-control input-md">
        </div>
    </div>


    <div class="form-group">
        <label class="col-md-4 control-label" for="textinput">Тип доступа</label>

        <div class="col-md-6">
            <select name="role" id="" class="form-control form-select">
                <option value="user">Пользователь</option>
                <option value="admin">Админ</option>
            </select>
        </div>
    </div>
<!-- Button -->
<div class="form-group">
  <div class="col-md-4 col-md-offset-6">
    <button class="btn btn-primary btn-lg">Обновить</button>
  </div>
</div>

</fieldset>
</form>
