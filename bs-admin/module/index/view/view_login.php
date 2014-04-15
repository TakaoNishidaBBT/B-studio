<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<link href="css/common.css" type="text/css" rel="stylesheet" media="all" />
<link href="css/login.css" type="text/css" rel="stylesheet" media="all" />
<title><?php echo $this->title ?></title></head>
<body>
	<script type="text/javascript">if(window != top) top.location.href='.';</script>
	<div id="HEADER"><h1><?php echo $this->site_title; ?></h1></div>

	<div class="login">
		<form method="post" action="." autocomplete="off">
			<div class="account">
				<table>
					<tbody>
						<tr>
							<th class="user">ログインID</th>
							<td><input name="user_id" type="text" class="textbox ime_off" size="30" maxlength="20" /></td>
						</tr>
						<tr>
							<th class="key">パスワード</th>
							<td><input name="password" type="password" class="textbox" size="30" maxlength="20" /></td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="icon">
				<img src="images/common/lock_icon.png" alt="login" width="27" height="27" />
			</div>

			<ul class="submit">
				<li><input class="login-button" name="login" type="submit" id="login" value="login"  /></li>
			</ul>
		</form>
	</div>

</body>
