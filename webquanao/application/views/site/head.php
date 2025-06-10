<meta charset="UTF-8">
<title>Ngọc Lan | Modern Fashion</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- jQuery -->
<script src="<?php echo public_url(); ?>js/jquery-3.1.1.js" type="text/javascript"></script>
<!-- Custom CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo public_url('site/'); ?>css/modern-style.css">
<!-- Shipment Page CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo public_url('site/'); ?>css/shipment-modern.css">
<!-- Ratings Plugin -->
<script type="text/javascript" src="<?php echo public_url('js/raty/jquery.raty.min.js') ?>"></script>
<script src="https://messenger.svc.chative.io/static/v1.0/channels/s9e9bba20-9d26-4e8b-ba6c-05defd7bc1c9/messenger.js?mode=livechat" defer="defer"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXX"></script>
<script>
	window.dataLayer = window.dataLayer || [];

	function gtag() {
		dataLayer.push(arguments);
	}
	gtag('js', new Date());
</script>
<script type="text/javascript">
	$(function() {
		$.fn.raty.defaults.path = "<?php echo public_url('js/raty/img'); ?>";
		$('.raty').raty({
			score: function() {
				return $(this).attr('data-score');
			},
			readOnly: true,
		});
	});
</script>

<style>
	.raty img {
		width: 16px !important;
		height: 16px !important;
	}
</style>