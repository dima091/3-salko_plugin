<style>
#twit-page {
	margin-top: 20px;
	1border: 1px solid black;
	height: 350px;
	width: 98%;
	background: white;
	padding: 20px;
	box-sizing: border-box;
}
#table-twit input {width: 400px;}
</style>

<div id='twit-page'>
	<h3 class="hndle ui-sortable-handle"><span>Twitter connect</span></h3>
	<p>Enter your twitter information</p><br/>
	<form action="options.php" method="post" class="settings">
		<?php 
			settings_fields( 'salko-widget' );
			do_settings_sections( 'salko-widget' );
		?>
		<table id='table-twit'>
			<tr>
				<td><label for="salko-twitter_a_t">Access Token</label></td>
				<td><input name="salko-widget[salko-twitter_a_t]" type='text' id='salko-twitter_a_t' value="<?php echo @self::$settings[ 'salko-twitter_a_t' ]; ?>"></td>
			</tr>
			<tr>
				<td><label for="salko-twitter_a_t_s">Access Token Secret</label></td>
				<td><input name="salko-widget[salko-twitter_a_t_s]" type='text' id='salko-twitter_a_t_s' value="<?php echo @self::$settings[ 'salko-twitter_a_t_s' ]; ?>"></td>
			</tr>
			<tr>
				<td><label for="salko-twitter_c_k">Consumer Key</label></td>
				<td><input name="salko-widget[salko-twitter_c_k]" type='text' id='salko-twitter_c_k' value="<?php echo @self::$settings[ 'salko-twitter_c_k' ]; ?>"></td>
			</tr>
			<tr>
				<td><label for="salko-twitter_c_s">Consumer Secret</label></td>
				<td><input name="salko-widget[salko-twitter_c_s]" type='text' id='salko-twitter_c_s' value="<?php echo @self::$settings[ 'salko-twitter_c_s' ]; ?>"></td>
			</tr>
			<tr>
				<td><label for="salko-twitter_count">twitts count</label></td>
				<td><input name="salko-widget[salko-twitter_count]" type='number' min=1 max=30 id='salko-twitter_count' value="<?php echo @self::$settings[ 'salko-twitter_count' ]; ?>"></td>
			</tr>
		</table>
		<input type="submit" name="save"  value='Save'>
	</form>
</div>