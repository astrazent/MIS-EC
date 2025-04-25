<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 clearpaddingr">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
		<ol class="breadcrumb">
			<li><a href="#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Trang chủ</a></li>
			<li class="active">Chi tiết giỏ hàng</li>
		</ol>
		<?php if (isset($message) && !empty($message)) { ?>
			<h4 style="color:red;margin-top: 20px"><?php echo $message; ?></h4>
		<?php }
		if ($total_items > 0) { ?>
			<div class="panel panel-info " style="margin-bottom: 15px">
				<div class="panel-heading">
					<h3 class="panel-title">GIỎ HÀNG ( <?php echo $total_items; ?> sản phẩm )</h3>
				</div>
				<div class="panel-body">
					<table class="table table-hover">
						<thead>
							<th>STT</th>
							<th>Tên sản phẩm</th>
							<th>Hình ảnh</th>
							<th>Số lượng</th>
							<th>Thành tiền</th>
							<th>Xóa</th>
						</thead>
						<tbody>
							<?php
							$i = 0;
							$total_price = 0;
							foreach ($carts as $items) {
								$total_price = $total_price + $items['subtotal']; ?>
								<tr>
									<td><?php echo $i = $i + 1 ?></td>
									<td><?php echo $items['name']; ?></td>
									<td><img src="<?php echo base_url('upload/product/' . $items['image_link']); ?>" class="img-thumbnail" alt="" style="width: 50px;"></td>
									<td>
                                        <button class="cart-sub" data-id="<?php echo $items['id']; ?>">-</button>
                                        <input type="text" class="qty-input" value="<?php echo $items['qty']; ?>" style="width: 30px;text-align: center;" readonly>
                                        <button class="cart-sum" data-id="<?php echo $items['id']; ?>">+</button>
                                    </td>
									<td><?php echo number_format($items['subtotal']); ?> VNĐ</td>
									<td><a href="<?php echo base_url('cart/del/' . $items['id']); ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
								</tr>
							<?php	}
							?>

							<tr>
								<td colspan="4">Tổng tiền</td>
								<td style="font-weight: bold;color:green"><?php echo number_format($total_price); ?> VNĐ</td>
								<td><a style="font-weight: bold;color: red" href="<?php echo base_url('cart/del'); ?>">Xóa toàn bộ</a></td>
							</tr>
							<tr>
								<td colspan="6">
                                    <a onclick="checkLoginBeforeOrder()" class="btn btn-success" style="cursor:pointer">Đặt mua</a>
                                </td>
                            </tr>
						</tbody>
					</table>

				</div>
			</div>
		<?php } else { ?>
			<div class="panel panel-info " style="margin-bottom: 15px">
				<div class="panel-heading">
					<h3 class="panel-title">GIỎ HÀNG ( 0 sản phẩm )</h3>
				</div>
				<div class="panel-body">
					<div class="text-center">
						<img src="<?php echo base_url('upload/cart-empty.png') ?>" alt="">
						<h4 style="color:red">Không có sản phẩm trong giỏ hàng</h4>
						<a href="<?php echo base_url('product/hot'); ?>" class="btn btn-success">Mua sắm</a>
					</div>

				</div>
			</div>

		<?php }
		?>



	</div>
</div>

<!-- Add this JavaScript code at the bottom of the file, before closing </div> tag -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.cart-sum, .cart-sub').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var action = $(this).hasClass('cart-sum') ? 'sum' : 'sub';
        var button = $(this);
        
        console.log('Button clicked:', id, action); // Debug log

        $.ajax({
            url: '<?php echo base_url("cart/update_ajax"); ?>/' + id + '/' + action,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response); // Debug log
                if(response.status == 'success') {
                    // Update quantity
                    button.siblings('.qty-input').val(response.qty);
                    // Update subtotal
                    button.closest('tr').find('td:eq(4)').html(response.subtotal + ' VNĐ');
                    // Update total price
                    $('td[colspan="4"]').next().html(response.total_price + ' VNĐ');
                    // Update cart count in header
                    $('.panel-title').html('GIỎ HÀNG ( ' + response.total_items + ' sản phẩm )');
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax Error:', error); // Debug log
            }
        });
    });
});
</script>
<style>
.cart-sum, .cart-sub {
    padding: 2px 8px;
    margin: 0 3px;
    cursor: pointer;
}
</style>

<!-- Thêm modal thông báo đăng nhập -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Thông báo</h4>
            </div>
            <div class="modal-body">
                <p>Bạn cần đăng nhập để đặt hàng</p>
            </div>
            <div class="modal-footer">
                <a href="<?php echo base_url('dang-nhap'); ?>" class="btn btn-primary">Đăng nhập</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Thêm script kiểm tra đăng nhập -->
<script>
function checkLoginBeforeOrder() {
    <?php if(!$this->session->userdata('user')): ?>
        $('#loginModal').modal('show');
    <?php else: ?>
        window.location.href = '<?php echo base_url('order'); ?>';
    <?php endif; ?>
}
</script>