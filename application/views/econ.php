<!DOCTYPE html>
<html>

<head>
	<title>Economic Indicators</title>		
</head>

<body>
	
	<?php echo form_open('econ'); ?>
		Name : <input type="text" name="filter_name" /> |
		start : <input type="text" name="filter_date_start" />
		end : <input type="text" name="filter_date_end" /> (timestamp) | 
		Type : 
		<select name="filter_type">
					<option value="0">All types</option>
			<?php foreach ($econlevels as $item): ?>
					<option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>		
			<?php endforeach; ?>
		</select>
		
		<input type="submit" value="search"/>
		
	</form>
	
	<hr /	>
	
	<table>
		<th>#</th> <th>Date</th> <th>Time</th> <th>C</th> <th>A</th> <th>M</th> <th>R</th> <th>Name</th> <th>Period</th>
		<th>Survey</th>	<th>Actual</th> <th>Prior</th> 	<th>Revised</th> <th>Forecast</th> 
		
		<?php foreach ($econforcasts as $item): ?>
		
			<tr>
			<td><?=$item['id']?></td>
			<td><?=date('m/d', $item['date'])?></td> 
			<td><?=date('g:i', $item['date'])?></td>
			<td><?=$item['countries_name']?></td>
			<td></td>
			<td></td>
			<td></td>
			<td><?=$item['econindicators_name']?></td>
			<td></td>
			<td></td>
			<td><?=$item['actual']?></td>
			<td></td>
			<td></td>
			<td><?=$item['forecast']?></td>
			</tr>
		
		<?php endforeach; ?>
		
	</table>
	
</body>

</html>
