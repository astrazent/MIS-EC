// Logic chọn địa chỉ
var citis = document.getElementById("city");
var districts = document.getElementById("district");
var wards = document.getElementById("ward");
var Parameter = {
	url: "http://localhost:8080/api/read-json",
	method: "GET",
	responseType: "application/json",
};
var promise = axios(Parameter);
promise.then(function (result) {
	renderCity(result.data);
});

function renderCity(data) {
	for (const x of data) {
		citis.options[citis.options.length] = new Option(x.Name, x.Id);
	}
	citis.onchange = function () {
		district.length = 1;
		ward.length = 1;
		if (this.value != "") {
			const result = data.filter((n) => n.Id === this.value);

			for (const k of result[0].Districts) {
				district.options[district.options.length] = new Option(k.Name, k.Id);
			}
		}
	};
	district.onchange = function () {
		ward.length = 1;
		const dataCity = data.filter((n) => n.Id === citis.value);
		if (this.value != "") {
			const dataWards = dataCity[0].Districts.filter(
				(n) => n.Id === this.value
			)[0].Wards;

			for (const w of dataWards) {
				wards.options[wards.options.length] = new Option(w.Name, w.Id);
			}
		}
	};
}
let recaptchaToken = "";

function onCaptchaSuccess(token) {
	recaptchaToken = token;
	document.getElementById("submitBtn").disabled = false;
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
document.addEventListener("DOMContentLoaded", function () {
	document
		.getElementById("submitBtn")
		.addEventListener("click", function (event) {
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

			// Giả lập giá trị input
			document.getElementById("name").value = "Nguyễn Văn A"; // Họ và tên giả
			document.getElementById("email").value = "phannguyen2300@gmail.com"; // Email giả
			document.getElementById("password").value = "12345678"; // Email giả
			document.getElementById("re-password").value = "12345678"; // Email giả
			document.getElementById("phone").value = "0912345678"; // Số điện thoại giả
			document.getElementById("address").value = "123 Đường ABC, Quận 1"; // Địa chỉ giả

			// Kiểm tra Họ và Tên
			let nameInput = document.getElementById("name");
			if (nameInput.value.trim() === "") {
				showError(nameInput, "Họ và tên không được để trống");
				isValid = false;
			} else {
				clearError(nameInput);
			}

			// Kiểm tra Email
			let emailInput = document.getElementById("email");
			let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (!emailRegex.test(emailInput.value.trim())) {
				showError(emailInput, "Email không hợp lệ");
				isValid = false;
			} else {
				clearError(emailInput);
			}

			// Kiểm tra mật khẩu
			let passwordInput = document.getElementById("password");
			let re_passwordInput = document.getElementById("re-password");
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

			// Kiểm tra Số điện thoại
			let phoneInput = document.getElementById("phone");
			let phoneRegex = /^[0-9]{8,11}$/;
			if (!phoneRegex.test(phoneInput.value.trim())) {
				showError(phoneInput, "Số điện thoại không hợp lệ (8-11 chữ số)");
				isValid = false;
			} else {
				clearError(phoneInput);
			}

			// Kiểm tra Địa chỉ
			let addressInput = document.getElementById("address");
			if (addressInput.value.trim() === "") {
				showError(addressInput, "Địa chỉ không được để trống");
				isValid = false;
			} else {
				clearError(addressInput);
			}

			// Kiểm tra Tỉnh/Thành
			let citySelect = document.getElementById("city");
			if (citySelect.value === "") {
				showError(citySelect, "Vui lòng chọn Tỉnh/Thành");
				isValid = false;
			} else {
				clearError(citySelect);
			}

			// Kiểm tra Quận/Huyện
			let districtSelect = document.getElementById("district");
			if (districtSelect.value === "") {
				showError(districtSelect, "Vui lòng chọn Quận/Huyện");
				isValid = false;
			} else {
				clearError(districtSelect);
			}

			// Kiểm tra Phường/Xã
			let wardSelect = document.getElementById("ward");
			if (wardSelect.value === "") {
				showError(wardSelect, "Vui lòng chọn Phường/Xã");
				isValid = false;
			} else {
				clearError(wardSelect);
			}

			// Nếu có lỗi, không gửi API
			if (!isValid) return;

			// Kiểm tra recaptcha
			if (!validateRecaptcha()) {
				return;
			}

			// Tạo object chứa dữ liệu cần gửi
			email = emailInput.value.trim();

			let formData = {
				name: nameInput.value.trim(),
				email: email,
				password: passwordInput.value.trim(),
				phone: phoneInput.value.trim(),
				address: addressInput.value.trim(),
				city: citySelect.options[citySelect.selectedIndex].text,
				district: districtSelect.options[districtSelect.selectedIndex].text,
				ward: wardSelect.options[wardSelect.selectedIndex].text,
				recaptcha: recaptchaToken,
			};

			// Gửi dữ liệu lên server qua fetch API (Fake API endpoint)
			// Cập nhật thời gian đếm ngược mỗi giây
			let countdown = 300; //ENV
			// Định nghĩa hàm ngoài
			async function myExternalFunction(url, email = null, interval = 1000) {
				let emailInfo = {
					email: email,
				};
				while (true) {
					try {
						const response = await fetch(url, {
							method: "POST",
							headers: {
								"Content-Type": "application/json",
							},
							body: JSON.stringify(emailInfo),
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
			fetch("http://localhost:8080/user/register", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify(formData),
			})
				.then((response) => response.text())
				.then((text) => {
					// Reset reCAPTCHA for the next submission
					grecaptcha.reset();
					document.getElementById("submitBtn").disabled = true;
					recaptchaToken = "";

					console.log("Raw response:", text); // Log dữ liệu để kiểm tra
					return JSON.parse(text); // Chuyển thành JSON thủ công
				})
				.then((data) => {
					// Hiển thị popup đặt hàng thành công
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
							window.location.href = "/dang-nhap";
						});
						myExternalFunction(
							"http://localhost:8080/user/check_validation_mail",
							email
						)
							.then((data) => {
								if (data.status === "success") {
									window.location.href = "/dang-nhap";
								}
							})
							.catch((error) => console.error("Error:", error));
					} else {
						Swal.fire({
							icon: "error",
							title: "Đăng kí thất bại",
							text: data.message,
							customClass: {
								confirmButton: "my-custom-button",
							},
							confirmButtonText: "Thử lại",
						});
					}
				})
				.catch((error) => {
					Swal.fire({
						icon: "error",
						title: "Lỗi!",
						customClass: {
							confirmButton: "my-custom-button",
						},
						text: "Đã có lỗi xảy ra, vui lòng thử lại.",
					});
					console.error(error);
				});
		});
});
