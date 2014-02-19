<form action="index.php" method="post">
				
				<?php if(isset($message)) echo $message;?><br/>
				
			<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input id="username" name='user' class="input" type="text" placeholder="Username"  style='height:28px; margin-top:9px;'></input></div>	
			<div class="input-prepend" style='margin-top:-10px;'><span class="add-on"><i class="icon-lock"></i></span><input id="password" name='userp' class="input" type="password" placeholder="Password"  style ='height:28px; margin-top:9px;'></input></div>
				  <input type="submit" name="submit" class='btn btn-success' value="   Login   " style="height:30px"/>
				  <input type="submit" name="cancel" class='btn btn-danger' value="   Cancel   " style="height:30px"/>
							
</form>
