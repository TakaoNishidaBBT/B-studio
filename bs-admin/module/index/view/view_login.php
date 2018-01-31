<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/login.css">
<title><?php echo $this->title ?></title></head>
<body>
	<script>if(window != top) top.location.href='.';</script>
	<div id="title-header"><h1><?php echo $this->site_title; ?></h1></div>
	<div class="login">
		<form method="post" action="." autocomplete="off">
			<div class="account">
				<table>
					<tbody>
						<tr>
							<th class="user"><?php echo __('Login ID'); ?></th>
							<td><input id="user_id" name="user_id" type="text" class="textbox ime_off" size="30" maxlength="20" autofocus /></td>
						</tr>
						<tr>
							<th class="key"><?php echo __('Password'); ?></th>
							<td><input id="password" name="password" type="password" class="textbox" size="30" maxlength="20" /></td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="icon">
				<img id="lock_icon" src="images/login/lock.svg" alt="lock" />
			</div>

			<ul class="submit">
				<li><input class="login-button" name="login" type="submit" id="login" value="login" /></li>
			</ul>
		</form>
	</div>
</body>
