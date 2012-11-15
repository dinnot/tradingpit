<?php echo validation_errors(); ?>

<?php echo form_open('news/news/index') ?>

	<textarea name="text"></textarea>	
	<input type="submit" name="submit" value="Search" /> 

</form>


<head>
	<script src = "<?php print base_url ()?>js/jquery.js"> </script>
		<script>
			$(document).ready(function(){
				$(".show").click(function(){
					$(".hide").hide();
					$(".show").show();
					$(this).hide();
					$(this).next().show();
				});
			});
			
			$(document).ready(function(){
				$(".hide").click(function(){ 
					$(this).hide();
					$(".show").show();
				});
			});
		</script>
</head> 

<table cellpadding = "10" >
	<tr>
		<td> Country   </td>
		<td> Date      </td>
		<td> Time      </td>
		<td> Headline  </td>
	</tr>
	
	<?php foreach ($news as $news_item): ?>

		<?php  if( $string_to_search == NULL || stristr( (string)$news_item["body"], $string_to_search )  ) :  ?>
		
			<tr>
				<td> <?php echo $news_item["name"] ;             ?>     </td>  
				<td> <?php echo date("M-d",$news_item["date"]) ; ?>     </td>  
				<td> <?php echo date("H:i",$news_item["date"]) ; ?>     </td>
				<td>
					<div class = "show"> 
				
					<?php 
						$L = strlen($news_item["body"]) ;
						
						if( $L <= 20 ) {
							echo $news_item["body"] ;         
						}
						else echo (substr($news_item["body"],0,17)."...") ;
					?>   
					</div>
					
					<div class = "hide" hidden = "hidden"> <?php echo $news_item["body"] ?> </div>
				</td> 
			</tr>
		
		<?php endif  ?>
		
	<?php endforeach ?>

</table>