let recaptchaToken = "";

let isValid = true;

function showError(input, message) {
	clearError(input);
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
			timer: 2500,
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
	const email = document.getElementById("email").value;
	const password = document.getElementById("password").value;
	let isValid = true;

	// Kiểm tra Email
	let emailInput = document.getElementById("email");
	let emailValue = emailInput.value.trim();
	let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	if (emailInput.value === "") {
		showError(emailInput, "Email không được để trống");
		isValid = false;
	} else if (!emailRegex.test(emailValue)) {
		showError(emailInput, "Email không hợp lệ");
		isValid = false;
	} else {
		clearError(emailInput);
	}

	// Kiểm tra mật khẩu
	let passwordInput = document.getElementById("password");
	let passwordValue = passwordInput.value.trim();
	if (passwordValue === "") {
		showError(passwordInput, "Password không được để trống");
		isValid = false;
	} else {
		clearError(passwordInput);
	}

	// Nếu có lỗi, không gửi API
	if (!isValid) return;

	// Gọi hàm kiểm tra trước khi gửi
	if (!validateRecaptcha()) {
		return;
	}

	fetch("http://localhost:8080/dang-nhap", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify({
			email: email,
			password: password,
			recaptcha: recaptchaToken,
		}),
	})
		.then((response) => {
			// Đọc response dưới dạng text trước
			return response.text().then((text) => {
				try {
					// Thử parse text thành JSON
					return JSON.parse(text);
				} catch (e) {
					// Nếu không parse được thì trả về text thuần
					return text;
				}
			});
		})
		.then((data) => {
			if (data.status == "success") {
				window.location.href = "/";
			} else {
				Swal.fire({
					icon: "error",
					title: "Đăng nhập thất bại",
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
		.catch((err) => {
			console.error("Lỗi:", err);
			alert("Có lỗi xảy ra: " + err.message);
			// Reset reCAPTCHA for the next submission
			grecaptcha.reset();
			recaptchaToken = "";
		});
});
