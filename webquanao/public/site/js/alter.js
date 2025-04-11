let recaptchaToken = "";

function onCaptchaSuccess(token) {
	recaptchaToken = token;
}

function validateRecaptcha() {
	if (!recaptchaToken) {
		// Loại thông báo: 'success' hoặc 'error'
		const type = "error"; // hoặc 'error'

		Swal.fire({
			toast: true,
			position: "top",
			icon: type,
			title:
				type === "success"
					? "Thành công! Dữ liệu đã được lưu."
					: "Vui lòng xác nhận reCAPTCHA trước khi gửi.",
			showConfirmButton: false,
			showCloseButton: true,
			timer: 4000,
			timerProgressBar: true,
			customClass: {
				popup: `custom-toast ${
					type === "success" ? "swal2-success-toast" : "swal2-error-toast"
				}`,
			},
			didOpen: (toast) => {
				toast.addEventListener("mouseenter", Swal.stopTimer);
				toast.addEventListener("mouseleave", Swal.resumeTimer);
			},
		});
		return false;
	}
	return true;
}

document.getElementById("submitBtn").addEventListener("click", function () {
	let isValid = true;

	function showError(input, message) {
		clearError(input);
		// Reset reCAPTCHA for the next submission
		grecaptcha.reset();
		recaptchaToken = "";

		let errorMsg = document.createElement("p");
		errorMsg.className = "text-red-500 text-xl mt-1";
		errorMsg.innerText = message;
		input.classList.add("border-red-500");
		input.parentNode.appendChild(errorMsg);
	}

	function clearError(input) {
		input.classList.remove("border-red-500");
		let errorMsg = input.parentNode.querySelector(".text-red-500");
		if (errorMsg) {
			errorMsg.remove();
		}
	}

	const password = document.getElementById("password").value;
	const repassword = document.getElementById("repassword").value;

	// Kiểm tra mật khẩu
	let passwordInput = document.getElementById("password");
	let re_passwordInput = document.getElementById("repassword");
	let passwordValue = passwordInput.value.trim();
	if (passwordValue.length < 8) {
		showError(passwordInput, "Password phải từ 8 kí tự trở lên");
		isValid = false;
	} else {
		clearError(passwordInput);
	}

	if (passwordInput.value !== re_passwordInput.value) {
		showError(re_passwordInput, "Mật khẩu nhập lại không khớp");
		isValid = false;
	} else {
		clearError(re_passwordInput);
	}

	// Nếu có lỗi, không gửi API
	if (!isValid) return;

	// Gọi hàm kiểm tra recaptcha trước khi gửi
	if (!validateRecaptcha()) {
		return;
	}

	const formData = {
		password: password,
		recaptcha: recaptchaToken,
	};
	// Gửi dữ liệu lên server qua fetch API (Fake API endpoint)
	// Cập nhật thời gian đếm ngược mỗi giây
	let countdown = 300; //ENV
	// Định nghĩa hàm ngoài
	async function myExternalFunction(url, password = null, interval = 1000) {
		let passwordInfo = {
			password: password,
		};
		while (true) {
			try {
				const response = await fetch(url, {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: JSON.stringify(passwordInfo),
				});
				const data = await response.json();

				if (data.status === "success") {
					return data;
				}
			} catch (error) {
				console.error("Fetch error:", error);
			}

			await new Promise((resolve) => setTimeout(resolve, interval));
		}
	}
	fetch("http://localhost:8080/quen-mat-khau", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify(formData),
	})
		.then((res) => res.text()) // Read the response as text first
		.then((text) => {
			try {
				// Reset reCAPTCHA for the next submission
				grecaptcha.reset();
				recaptchaToken = "";

				// Try parsing the response text as JSON
				const data = JSON.parse(text);
				// Hiển thị popup đổi mật khẩu thành công
				if (data.status == "success") {
					Swal.fire({
						icon: "info", // Biểu tượng thông tin
						title: "Sắp xong rồi...", // Tiêu đề
						text: data.message,
						customClass: {
							confirmButton: "my-custom-button", // Tùy chỉnh lớp nút xác nhận
						},
						confirmButtonText: "OK", // Văn bản của nút xác nhận
						showConfirmButton: false, // Ẩn nút xác nhận
						timer: countdown * 1000, // Thời gian hiển thị popup (10 giây)
						timerProgressBar: true, // Hiển thị thanh tiến trình đếm ngược
					}).then(() => {
						// Sau khi popup đóng hoặc khi thời gian hết, chuyển hướng
						window.location.href = "/";
					});
					myExternalFunction(
						"http://localhost:8080/user/check_forgot_password_mail",
						password
					)
						.then((data) => {
							if (data.status === "success") {
								Swal.fire({
									icon: "success",
									title: "Đổi mật khẩu thành công",
									text: data.message,
									customClass: {
										confirmButton: "my-custom-button",
									},
									confirmButtonText: "OK",
								}).then((result) => {
									if (result.isConfirmed) {
										// Chuyển hướng về trang chủ
										window.location.href = "/";
									}
								});
							} else {
								Swal.fire({
									icon: "error",
									title: "Xác thực mail thất bại",
									text: data.message,
									customClass: {
										confirmButton: "my-custom-button",
									},
									confirmButtonText: "Thử lại",
								});
								// Reset reCAPTCHA for the next submission
								grecaptcha.reset();
								recaptchaToken = "";
							}
						})
						.catch((error) => console.error("Error:", error));
				} else {
					Swal.fire({
						icon: "error",
						title: "Đổi mật khẩu thất bại",
						text: data.message,
						customClass: {
							confirmButton: "my-custom-button",
						},
						confirmButtonText: "Thử lại",
					});
				}
			} catch (err) {
				// Handle error if JSON parsing fails
				console.error("Lỗi:", err);
			}
		})
		.catch((err) => {
			console.error("Lỗi:", err);
			alert("Có lỗi xảy ra.");
		});
});
