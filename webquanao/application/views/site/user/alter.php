<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('site/head', $this->data); ?>

    <!-- tích hợp reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer>
    </script>

    <style>
        .g-recaptcha>div:first-child {
            margin: 10px auto 20px auto;
        }
    </style>
<!-- tích hợp reCAPTCHA -->
</head>

<body>
    <div class="container">
        <?php $this->load->view('site/header', $this->data); ?>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding" style="margin-top: 15px;">
                <ol class="breadcrumb">
                    <li><a href="#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home</a></li>
                    <li class="active">Đăng nhập</li>
                </ol>
                <div class="panel panel-info ">
                    <?php echo form_error('oldpassword'); ?>
                    <?php if (isset($message_success) && !empty($message_success)) { ?>
                        <h4 style="color:green;text-align: center;margin-top: 30px"><?php echo $message_success; ?></h4>

                    <?php } ?>
                    <?php if (isset($message_fail) && !empty($message_fail)) { ?>
                        <h4 style="color:red;text-align: center;margin-top: 30px"><?php echo $message_fail; ?></h4>
                    <?php } ?>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="<?php echo base_url('user/changepassword'); ?>" onsubmit="return validateRecaptcha();">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-offset-2 col-sm-2 control-label">Mật khẩu cũ</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" id="inputEmail3" placeholder="" name="oldpassword">
                                </div>
                                <div class="col-sm-3">
                                    <?php echo form_error('password'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-offset-2 col-sm-2 control-label">Mật khẩu mới</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" id="inputEmail3" placeholder="" name="password">
                                </div>
                                <div class="col-sm-3">
                                    <?php echo form_error('password'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-offset-2 col-sm-2 control-label">Nhập lại mật khẩu</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" id="inputEmail3" placeholder="" name="repassword">
                                </div>
                                <div class="col-sm-3">
                                    <?php echo form_error('repassword'); ?>
                                </div>
                            </div>

                            <div class="g-recaptcha form-group" style="margin: 0;" data-sitekey="6LcKdPUqAAAAAGv-BwfXyqkrqpTuVEUCQLGwbG6Z"></div>

                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-2">
                                    <button type="submit" class="btn btn-success">Cập nhật</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <?php $this->load->view('site/footer', $this->data); ?>
    </div>
    <!-- recaptcha srcipt -->
    <script>
        function validateRecaptcha() {
            var recaptchaResponse = document.getElementById("g-recaptcha-response").value;
            if (!recaptchaResponse) {
                alert("Vui lòng xác nhận reCAPTCHA trước khi gửi!");
                return false; // Chặn submit form
            }
            return true; // Cho phép gửi form
        }
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
    <!-- recaptcha srcipt -->
    <script src="<?php echo public_url('site/'); ?>bootstrap/js/bootstrap.min.js"></script>
</body>

</html>