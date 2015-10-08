<?php
if (!is_user_logged_in())
{
	return;
}
else
{
	switch ( $gm_role )
	{
		case "administrator":
			$user_role_permission = "manage_options";
			break;

		case "editor":
			$user_role_permission = "publish_pages";
			break;

		case "author":
			$user_role_permission = "publish_posts";
			break;

		case "contributor":
			$user_role_permission = "edit_posts";
			break;

		case "subscriber":
			$user_role_permission = "read";
			break;
	}
	if ( ! current_user_can ( $user_role_permission ) )
	{
		return;
	}
	else
	{
		?>
		
		<div class="page-container">
			<div id="manage_messages" style="display:none;">
				<div class="radio">
					<input type="radio" value="success" checked="checked" />
				</div>
			</div>
		<?php
	}
}
?>