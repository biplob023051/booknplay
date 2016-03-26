<style>.fancybox-nav{width:0px;}</style>
<div class="row-fluid menu">
	<a class="btn btn-navbar visible-phone" data-toggle="collapse"
		data-target=".nav-collapse"> <span class="icon-list"></span>
	</a>
	<div class="navbar navbar-inverse nav-collapse">
		<div class="navbar-inner">
			<ul class="nav">
				<li><?php echo $this->EBHtml->link('<i class="icon-print"></i>'.__("Ground"), array("controller"=>"grounds", "action"=>"index"), array('escape'=>false)); ?></li>
				<li><?php echo $this->EBHtml->link('<i class="icon-user"></i>'.__("Users"), array("controller"=>"users", "action"=>"index"), array('escape'=>false)); ?></li>
				<li><?php echo $this->EBHtml->link('<i class="icon-user"></i>'.__("Booking"), array("controller"=>"bookings", "action"=>"index"), array('escape'=>false)); ?></li>
				<li><?php echo $this->EBHtml->link('<i class="icon-user"></i>'.__("Schedule"), array("controller"=>"schedules", "action"=>"index"), array('escape'=>false)); ?></li>
				
				<li style="display:none;" class="dropdown"><a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-plane"></i>&nbsp;Shipping <b class="caret"></b> </a>
					<ul class="dropdown-menu">
						<li><?php echo $this->EBHtml->link('<i class="icon-briefcase"></i>'.__("Shiiping Group"), array("controller"=>"shipping_groups", "action"=>"index"), array('escape'=>false)); ?></li>
					</ul>
				</li>
				<li><?php echo $this->EBHtml->link('<i class="icon-cog"></i>'.__("Group"), array("controller"=>"groups", "action"=>"index"), array('escape'=>false)); ?></li>
				<li><?php echo $this->EBHtml->link('<i class="icon-cog"></i>'.__("Type"), array("controller"=>"types", "action"=>"index"), array('escape'=>false)); ?></li>
				<li><?php echo $this->EBHtml->link('<i class="icon-cog"></i>'.__("Settings"), array("controller"=>"users", "action"=>"settings"), array('escape'=>false)); ?></li>
				<li><a href="<?php echo $this->webroot;?>" target="_blank"><i class="icon-globe"></i>&nbsp;View Live site</a></li>
			</ul>
		</div>
	</div>
</div>
