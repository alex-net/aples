<?php 

$this->title = 'Управление яблоками';

\backend\assets\ApleAsset::register($this);

?>
<div class="statusmess"></div>
<div class="added-form">
	<input type="text" size="10" name="count" placeholder="Число яблок" required=""><input type="button" name="add" value="Добавить">
</div>
<table class="aples-list" width="100%" border="1">
	<thead>
		<tr>
			<th>№</th>
			<th>Цвет</th>
			<th>Статус</th>
			<th>Съедено, %</th>
			<th>Действия</th>

		</tr>
	</thead>
	<tbody></tbody>
</table>