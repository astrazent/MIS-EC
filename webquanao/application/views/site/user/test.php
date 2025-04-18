<!DOCTYPE html>
<html lang="en">

<head>
	<?php $this->load->view('site/head', $this->data); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo public_url('site/'); ?>css/validation.css">
</head>

<body>
	<div class="container">
        <iframe src="<?php echo site_url('kt'); ?>" style="width: 100%; height: auto; border: none;"></iframe>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
				
			</div>
		</div>
		<?php $this->load->view('site/footer', $this->data); ?>
	</div>
	<script src="<?php echo public_url('site/'); ?>bootstrap/js/bootstrap.min.js"></script>
</body>

</html>