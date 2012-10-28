<?php echo validation_errors(); ?>

<?php echo form_open('news/index') ?>

	<textarea name="text"></textarea>	
	<input type="submit" name="submit" value="Search" /> 

</form>

<table border ="1">
	<tr>
		<td> Country   </td>
		<td> Date      </td>
		<td> Time      </td>
		<td> Headline  </td>
	</tr>
	
	<?php foreach ($news as $news_item): ?>

		<?php  if( $string_to_search == NULL || stristr( (string)$news_item["headline"], $string_to_search )  ) :  ?>
		
			<tr>
				<td> <?php echo $news_item["name"] ;             ?>     </td>  
				<td> <?php echo date("M-d",$news_item["date"]) ; ?>     </td>  
				<td> <?php echo date("H:i",$news_item["date"]) ; ?>     </td>
				<td> <?php echo $news_item["headline"] ;         ?>     </td> 
			</tr>
		
		<?php endif  ?>
		
	<?php endforeach ?>

</table>