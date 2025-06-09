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
	<?php $this->load->view('site/header',$this->data); ?>

	<!-- Main Content Container -->
	<div class="container py-4">
		<!-- Breadcrumb and Messages -->
		<div class="row">
			<div class="col-12 mb-3">
				<?php $this->load->view('admin/message.php'); ?>
			</div>
		</div>
		
		<!-- Main Content Row -->
		<div class="row">
			<!-- Sidebar Column -->
			<div class="col-md-3">
				<?php $this->load->view('site/sidebar',$this->data); ?>
			</div>
			
			<!-- Content Column -->
			<div class="col-md-9">
				<?php $this->load->view($temp,$this->data); ?>
			</div>
		</div>
	</div>
	
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
                userToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle('show');
                    
                    // Close dropdown when clicking outside
                    document.addEventListener('click', function closeDropdown(event) {
                        if (!event.target.closest('.user-dropdown')) {
                            dropdownMenu.classList.remove('show');
                            document.removeEventListener('click', closeDropdown);
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>