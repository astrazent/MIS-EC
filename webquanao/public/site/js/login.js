function handleLogin(event) {
    event.preventDefault(); // Ngăn form submit mặc định

    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    if (email === "" || password === "") {
        alert("Vui lòng nhập đầy đủ email và mật khẩu!");
        return;
    }

    // Chuyển hướng hoặc gửi AJAX xử lý đăng nhập
    window.location.href = "<?php echo base_url('user/login'); ?>?email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password);
}
