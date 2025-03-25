<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 clearpaddingr">
    <div class="panel panel-info" style="margin-bottom: 15px">
        <div class="panel-heading">
            <h3 class="panel-title">Danh sách đơn hàng</h3>
        </div>
        <div class="panel-body">
            <?php if (!empty($orders)) { ?>
                <button id="cancel-orders-btn" class="btn btn-danger" style="display:none; margin-bottom: 10px;">Hủy đơn hàng</button>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th></th>
                            <th>Lượng</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($orders as $order) { ?>
                            <tr>
                                <td><input type="checkbox" class="order-checkbox" value="<?php echo $order->order_id; ?>"></td>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $order->product_name; ?></td>
                                <td>
                                    <img src="<?php echo base_url('upload/product/' . $order->product_image); ?>"
                                        class="img-thumbnail" alt="<?php echo $order->product_name; ?>"
                                        style="width: 50px;">
                                </td>
                                <td><?php echo $order->total_qty; ?></td>
                                <td><?php echo number_format($order->total_price); ?> VNĐ</td>
                                <td>
                                    <?php
                                    if ($order->payment_method == 'NO') {
                                        echo "<span class='label label-danger'>Chưa thanh toán</span>";
                                    } else if ($order->payment_method == 'YES') {
                                        echo "<span class='label label-success'>Đã thanh toán</span>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($order->status == 0) {
                                        echo "<span class='label label-warning'>Đang chờ xử lý</span>";
                                    } else if ($order->status == 1) {
                                        echo "<span class='label label-primary'>Đã xác nhận</span>";
                                    } else if ($order->status == 2) {
                                        echo "<span class='label label-info'>Đang vận chuyển</span>";
                                    } else if ($order->status == 3) {
                                        echo "<span class='label label-success'>Hoàn thành</span>";
                                    }
                                    ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', $order->created); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <h4 class="text-center text-danger">Không có đơn hàng nào!</h4>
            <?php } ?>
        </div>
        <div class="text-center">
            <?php echo $pagination; ?>
        </div>
    </div>
</div>

<!-- JavaScript xử lý chọn đơn hàng -->
<script>
    $(document).ready(function() {
        $(".order-checkbox").on("change", function() {
            let selectedOrders = $(".order-checkbox:checked").length;

            if (selectedOrders > 0) {
                $("#cancel-orders-btn").show();
            } else {
                $("#cancel-orders-btn").hide();
            }

            $(this).closest("tr").toggleClass("selected", $(this).is(":checked"));
        });

        $("#select-all").on("change", function() {
            $(".order-checkbox").prop("checked", $(this).prop("checked")).trigger("change");
        });

        $("#cancel-orders-btn").on("click", function() {
            let orderIds = $(".order-checkbox:checked").map(function() {
                return $(this).val();
            }).get();

            if (orderIds.length === 0) return;

            if (!confirm("Bạn có chắc muốn hủy các đơn hàng đã chọn không?")) return;

            $.ajax({
                url: "<?php echo base_url('shipment/cancel_orders'); ?>",
                type: "POST",
                data: {
                    order_ids: orderIds
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function() {
                    alert("Có lỗi xảy ra, vui lòng thử lại!");
                }
            });
        });
    });
</script>

<style>
    .selected {
        background-color: #dcdcdc !important;
    }
</style>