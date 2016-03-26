<div class="navbar">
	<div class="navbar-inner">
		<div class="container-fluid">
			<ul class="nav pull-right">

				<li id="fat-menu" class="dropdown"><a href="#" id="drop3"
					role="button" class="dropdown-toggle" data-toggle="dropdown"> 
					<i class="icon-user"></i> <span style="text-transform:capitalize;"> Hello <?php echo $this->Session->read('Auth.User.name')?> <i class="icon-caret-down"></i></span>
				</a>

					<ul class="dropdown-menu">
						<!-- <li><a tabindex="-1" href="#"><i class="icon-cog"></i>&nbsp;Account</a></li> -->
						<li><a tabindex="-1" href="<?php echo $this->webroot;?>users/admin_password"><i class="icon-edit"></i>&nbsp;Change Password</a></li>
						<li class="divider"></li>
						<li><a tabindex="-1" href="<?php echo $this->webroot.'users/logout';?>"><i class="icon-off"></i>&nbsp;Logout</a></li>
					</ul></li>

			</ul>
			<a class="brand" href="<?php echo $this->webroot;?>" style="line-height:10px;font-size:14px;"><span class="second">BookNPlay</span></a>
		</div>
	</div>
</div>