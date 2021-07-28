<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">

<?php
$keys = array_keys ($rows[0]);
?>
<div class="container">
<table id="read_<?= $table ?>" class="table">
<thead>
<tr>
<?php foreach ($keys as $key) {
if(isset($filter)){ 
if(in_array($key, $filter)) continue;} ?>
<?php if(isset($this->config['headers'][$key])) { ?>
	<th><?= $this->config['headers'][$key] ?></th>
<?php } else { ?>
	<th><?= ucfirst(str_replace('_',' ',$key)) ?></th>
<?php } ?>
<?php } ?>
<th>Ред.</th>
<th>Уд.</th>
</tr>
</thead>
<tbody>
<?php foreach ($rows as $key => $row) { ?>
<tr>
<?php  foreach ($row as $key => $value) {
if(isset($filter)){ 
if(in_array($key, $filter)) continue;} ?>
    <td>
    	<?php if($key == 'url'): ?>    		
    	<a data-toggle="tooltip" data-placement="top" title="<?= $value?>" href="<?= (!strstr($value, 'http://') && !strstr( $value, 'https://') ) ? 'http://' : '' ?><?= $value?>"><?= $value?></a>
    	<?php elseif($key == 'slug'): ?>
        <input type="text" value="<?= HTTP ?><?= $_SERVER['HTTP_HOST']?>/<?= $value?>" class="form-control">
        <div class="copy" title="Копировать ссылку"><img src="/static/img/copy.svg" alt="" width="40"></div>
    	<?php else: ?>
    	<?= $value?>
    	<?php endif; ?>
    </td>
<?php } ?>
<td><a href="/admin/users/edit?id=<?= $row['id']?>">Ред.</a></td>
<td><a href="/admin/users/del?id=<?= $row['id']?>">Уд.</a></td>
</tr>
<?php } ?>
</tbody>
</table>
    <div>
        <a href="/admin/users/create/" class="btn btn-primary">Создать пользователя</a>
    </div>
</div>

<!-- <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function(){
        //$('#read_<?= $table ?>').DataTable();
});
</script> -->
<style>
    .copy{
        display: inline-block;
        width: 20%;
        margin: 0;
    }
    .form-control{
        width: 70%;
        margin: 0;
        display: inline-block;
    }
</style>