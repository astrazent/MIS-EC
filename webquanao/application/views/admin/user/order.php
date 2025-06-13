<div class="row">
	<ol class="breadcrumb">
		<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
		<li class="active">Đơn đặt hàng</li>
	</ol>
</div><!--/.row-->

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading">
			<div class="col-md-8">Đơn đặt hàng của <?php echo $user->name; ?></div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr class="info">										
								<th class="text-center">STT</th>
								<th>Tên khách hàng</th>
								<th>Ngày đặt</th>
								<th>Số ĐT</th>
								<th>Giá tiền</th>
								<th>Trạng thái</th>		
								<th>Hành động</th>
							</tr>
						</thead>
						<tbody>

							<?php 
								$stt = 0;
							foreach ($order as $value) { 
								$stt = $stt + 1;
								?>
								<tr>
									<td style="vertical-align: middle;text-align: center;"><strong><?php echo $stt; ?></strong></td>
									<td><strong><?php echo $value->delivery_name; ?></strong></td>
									<td><strong><?php echo $value->created; ?></strong></td>
									<td><strong><?php echo $value->delivery_phone; ?></strong></td>
									<td><strong><?php echo number_format($value->amount); ?></strong> VNĐ</td>
									<td>
                                        <?php switch ($value->status) {
                                            case '0':
                                                echo "<p style='color:red'><i class='fa fa-clock'></i> Đang chờ</p>";
                                                break;
                                            case '1':
                                                echo "<p style='color:orange'><i class='fa fa-check-circle'></i> Đã xác nhận</p>";
                                                break;
                                            case '2':
                                                echo "<p style='color:blue'><i class='fa fa-truck'></i> Đang vận chuyển</p>";
                                                break;
                                            case '3':
                                                echo "<p style='color:green'><i class='fa fa-check'></i> Hoàn thành</p>";
                                                break;
                                            default:
                                                echo "<p><i class='fa fa-question-circle'></i> Không xác định</p>";
                                                break;
                                        } ?>
                                    </td>
									<td class="list_td aligncenter">
							            <a href="<?php echo admin_url('user/detail/'.$value->id); ?>" title="Chi tiết"><span class="glyphicon glyphicon-list-alt"></span></a>&nbsp;&nbsp;&nbsp;
							            <a href="<?php echo admin_url('user/deldetail/'.$value->id); ?>" title="Xóa"> <span class="glyphicon glyphicon-remove" onclick=" return confirm('Bạn chắc chắn muốn xóa')"></span> </a>
								    </td>    
				                </tr>
							<?php } ?>

			    		</tbody>

					</table>

					
					
				</div>
			</div>
		</div>
	</div>
</div><!--/.row-->
