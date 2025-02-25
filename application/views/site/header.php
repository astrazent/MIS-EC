<div class="row" style="margin-top: 8px;height: 110px">
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 clearpadding">
				<a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>upload/logo.png" alt="" class="img-responsive"></a>
			</div>
			<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 clearpadding">
				<a href="#"><img style="max-height: 110px;width: 100%" src="<?php echo base_url(); ?>upload/banner.jpg" alt="" class="img-responsive"></a>
			</div>
		</div>

		<script src="https://sf-cdn.coze.com/obj/unpkg-va/flow-platform/chat-app-sdk/1.2.0-beta.2/libs/oversea/index.js"></script>
<!-- <script>
  new CozeWebSDK.WebChatClient({
    config: {
      bot_id: '7473807055259222032',
    },
    componentProps: {
      title: 'Coze',
    },
    auth: {
      type: 'token',
      token: 'pat_TPwfXcuOp7A3ALlJJiy0G2f3lIGPiwo3KP3mvVYlyntuOWf9H88viMRZUp1bSi8f',
      onRefreshToken: function () {
        return 'pat_TPwfXcuOp7A3ALlJJiy0G2f3lIGPiwo3KP3mvVYlyntuOWf9H88viMRZUp1bSi8f'
      }
    }
  });
</script> -->
// code chattive
<script src="https://messenger.svc.chative.io/static/v1.0/channels/s1789b3b1-6aa9-4521-94cc-d722aa27a3d0/messenger.js?mode=livechat" defer="defer"></script>
//code chattive

		<div class="row">
			<nav class="navbar navbar-info re-navbar" >
			  <div class="container-fluid re-container-fluid">
			    <!-- Brand and toggle get grouped for better mobile display -->
			    <div class="navbar-header">
			      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			      </button>
			      	<a class="navbar-brand" href="#">--- Menu ---</a>
			    </div>
				<!-- Collect the nav links, forms, and other content for toggling -->
			    <div class="collapse navbar-collapse re-navbar-collapse" id="bs-example-navbar-collapse-1">
			      <ul class="nav navbar-nav">
			        <li class="active"><a href="<?php echo base_url(); ?>"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> HOME<span class="sr-only">(current)</span></a></li>

			        <li><a href="<?php echo base_url('moi'); ?>">Mới</a></li>
			        <li><a href="<?php echo base_url('ban-chay'); ?>">Bán chạy</a></li>
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Thời trang<span class="caret"></span></a>
			          <ul class="dropdown-menu" id="re-dropdown-menu">
			          <?php foreach ($catalog as $value)
			           {
			           		$name = covert_vi_to_en($value->name);
	  						$name = strtolower($name);
			            ?>
			          	<li><a style="color: #337ab7;padding: 10px 20px;" href="<?php echo base_url($name.'-c'.$value->id); ?>"><?php echo $value->name; ?></a></li>
			          <?php } ?>
			          </ul>
			        </li>
			        <li><a href="<?php echo base_url('khuyen-mai'); ?>">Khuyến mại</a></li>
			       </ul>
			      <ul class="nav navbar-nav navbar-right">
			        <?php $this->load->view('site/cart/cart_sh'); ?>
			        
			        <?php if (!isset($user)) { ?>
			        	<li><a href="<?php echo base_url('dang-nhap'); ?>">Đăng nhập</a></li>
			        <?php }else{ ?>
						<li class="dropdown">
				          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Xin chào: <?php echo $user->name; ?><span class="caret"></span></a>
				          <ul class="dropdown-menu">
				          	<li><a href="<?php echo base_url('user'); ?>">Tài khoản</a></li>
						    <li role="separator" class="divider"></li>
						    <li><a href="<?php echo base_url('user/logout'); ?>">Đăng xuất</a></li>
				          </ul>
				        </li>
			        <?php } ?>
			      </ul>
			    </div><!-- /.navbar-collapse -->
			    
			  </div><!-- /.container-fluid -->
			</nav>
		</div>