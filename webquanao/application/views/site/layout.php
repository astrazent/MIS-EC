<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('site/head',$this->data); ?>
</head>
<body>
	<!-- Announcement Bar -->
	<div class="announcement-bar">
		<div class="container">
			<div class="announcement-content">
				<p>Miễn phí vận chuyển Cho đơn hàng trên 500.000 VNĐ</p>
			</div>
		</div>
	</div>

	<!-- Header -->
	<?php 
	// Better fix for $user_info variable - make it available locally in header
	if(isset($user)) {
		$this->data['user_info'] = $user;
	}
	$this->load->view('site/header',$this->data); 
	?>

	<!-- Search Overlay -->
	<div class="search-overlay">
		<div class="container">
			<div class="search-wrapper">
				<form action="<?php echo base_url('text-search'); ?>" method="POST" class="search-form">
					<input type="text" name="key" placeholder="Search for products..." class="search-input" required>
					<button type="submit" class="search-submit">
						<i class="fas fa-search"></i>
					</button>
				</form>
				<button class="close-search">
					<i class="fas fa-times"></i>
				</button>
			</div>
		</div>
	</div>

	<!-- Main Content -->
	<?php $this->load->view($temp,$this->data); ?>

	<!-- Footer -->
	<?php $this->load->view('site/footer',$this->data); ?>

	<!-- Back to Top Button -->
	<a href="#" class="back-to-top">
		<i class="fas fa-chevron-up"></i>
	</a>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script src="<?php echo base_url(); ?>public/site/js/modern-script.js"></script>
	
	<script>
	// User dropdown toggle
	document.addEventListener('DOMContentLoaded', function() {
	  const userToggle = document.querySelector('.user-toggle');
	  if (userToggle) {
		userToggle.addEventListener('click', function(event) {
		  event.preventDefault();
		  event.stopPropagation();
		  
		  const dropdown = document.querySelector('.user-dropdown .dropdown-menu');
		  if (dropdown) {
			dropdown.classList.toggle('show');
		  }
		});
		
		// Close when clicking elsewhere
		document.addEventListener('click', function(event) {
		  if (!event.target.closest('.user-dropdown')) {
			const dropdown = document.querySelector('.user-dropdown .dropdown-menu');
			if (dropdown) {
			  dropdown.classList.remove('show');
			}
		  }
		});
	  }
	});
	</script>
</body>
</html>