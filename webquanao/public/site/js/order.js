// Logic chọn địa chỉ
var citis = document.getElementById("city");
var districts = document.getElementById("district");
var wards = document.getElementById("ward");
var Parameter = {
	url: "https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json",
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

// Hiệu ứng hiển thị chi tiết phương thức thanh toán
function highlightPayment(selectedInput) {
	// Xóa in đậm của tất cả tiêu đề thanh toán
	document.querySelectorAll(".payment-title").forEach((title) => {
		title.classList.remove("font-bold");
	});

	// Lấy phần tử `span` kế bên input radio và in đậm nó
	selectedInput.nextElementSibling.nextElementSibling.classList.add(
		"font-bold"
	);
}

document.addEventListener("DOMContentLoaded", function () {
	const paymentOptions = document.querySelectorAll(".payment-option");
	const detailsSections = {
		bank: document.getElementById("bank-details"),
		pos: document.getElementById("pos-details"),
		vnpay: document.getElementById("vnpay-details"),
		vietqr: document.getElementById("vietqr-details"),
	};

	paymentOptions.forEach((option) => {
		option.addEventListener("change", function () {
			highlightPayment(option);
			// Ẩn tất cả nội dung thanh toán
			Object.values(detailsSections).forEach((section) =>
				section.classList.add("hidden")
			);

			// Hiển thị nội dung tương ứng với lựa chọn
			if (detailsSections[this.value]) {
				detailsSections[this.value].classList.remove("hidden");
			}
		});
	});
});

// Kiểm tra tính hợp lệ của form
document.addEventListener("DOMContentLoaded", function () {
	// Add event listener to the form submit
	const orderForm = document.getElementById("orderForm");
	if (orderForm) {
		orderForm.addEventListener("submit", function(event) {
			let isValid = true;
			let selectedPayment = document.querySelector('input[name="payment"]:checked');
			let paymentError = document.getElementById("payment-error");
			
			// Only validate payment if it's required
			if (!selectedPayment) {
				paymentError.classList.remove("hidden"); // Hiển thị cảnh báo
				isValid = false;
			} else {
				paymentError.classList.add("hidden"); // Ẩn cảnh báo nếu đã chọn
			}
			
			if (!isValid) {
				event.preventDefault();
				return false;
			}
			
			// If payment is VNPAY, handle it differently - form should not submit directly
			if (selectedPayment && selectedPayment.value === "vnpay") {
				event.preventDefault();
				
				// Get the form data
				const formData = new FormData(orderForm);
				const formDataObject = {};
				formData.forEach((value, key) => {
					formDataObject[key] = value;
				});
				
				// Send to VNPAY endpoint
				console.log("Sending payment to URL:", paymentEndpoint);
				
				fetch(paymentEndpoint, {
					method: "POST",
					headers: { "Content-Type": "application/json" },
					body: JSON.stringify(formDataObject),
				})
				.then((response) => response.json())
				.then((data) => {
					if (data.payment_url) {
						// Redirect to VNPAY
						window.location.href = data.payment_url;
					}
				})
				.catch((error) => {
					console.error("Error:", error);
					Swal.fire({
						icon: "error",
						title: "Lỗi!",
						text: "Không thể kết nối đến cổng thanh toán. Vui lòng thử lại sau.",
						customClass: {
							confirmButton: "my-custom-button",
						},
						confirmButtonText: "OK",
					});
				});
				
				return false;
			}
			
			// Continue with normal form submission for other payment methods
			return true;
		});
	}
	
	// Continue with the existing document.getElementById("submitBtn") code
	// (This is needed for browsers that don't support the form submission directly)
	const submitBtn = document.getElementById("submitBtn");
	if (submitBtn) {
		submitBtn.addEventListener("click", function (event) {
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

			// Là tuỳ chọn
			let message = document.getElementById("message").value;

			// Nếu có lỗi, không gửi API
			if (!isValid) return;

			// Tạo object chứa dữ liệu cần gửi
			let formData = {
				name: nameInput.value.trim(),
				email: emailInput.value.trim(),
				phone: phoneInput.value.trim(),
				address: addressInput.value.trim(),
				city: citySelect.value,
				district: districtSelect.value,
				ward: wardSelect.value,
				message: message,
				payment: selectedPayment.value,
			};

			if (selectedPayment.value == "cash" || selectedPayment.value == "vietqr" || selectedPayment.value == "pos") {
				// Gửi dữ liệu lên server qua fetch API
				console.log("Sending order to URL:", completeEndpoint);
				
				fetch(completeEndpoint, {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: JSON.stringify(formData),
				})
					.then((response) => {
						console.log("Response status:", response.status);
						// Check if the response status is ok (200-299)
						if (!response.ok) {
							if (response.status === 404) {
								throw new Error("Không tìm thấy URL. Vui lòng kiểm tra lại đường dẫn.");
							} else if (response.status >= 500) {
								throw new Error("Lỗi máy chủ. Vui lòng thử lại sau.");
							} else {
								throw new Error("Lỗi yêu cầu. Mã trạng thái: " + response.status);
							}
						}
						return response.text();
					})
					.then((text) => {
						console.log("Raw response:", text); // Log dữ liệu để kiểm tra
						try {
							return JSON.parse(text); // Chuyển thành JSON thủ công
						} catch (e) {
							console.error("Error parsing JSON:", e);
							throw new Error("Invalid JSON response from server");
						}
					})
					.then((data) => {
						// Hiển thị popup đặt hàng thành công
						if (data.status == "success") {
							Swal.fire({
								icon: "success",
								title: "Đặt hàng thành công!",
								text: data.message,
								customClass: {
									confirmButton: "my-custom-button",
								},
								confirmButtonText: "OK",
							}).then(() => {
								window.location.href = baseUrl;
							});
						} else {
							Swal.fire({
								icon: "error",
								title: "Đặt hàng thất bại!",
								text: data.message,
								customClass: {
									confirmButton: "my-custom-button",
								},
								confirmButtonText: "Thử lại",
							});
						}
					})
					.catch((error) => {
						console.error("Error:", error);
						let errorMessage = "Đã có lỗi xảy ra, vui lòng thử lại.";
						
						// More specific error messages
						if (error.message.includes("Failed to fetch") || error.message.includes("NetworkError")) {
							errorMessage = "Không thể kết nối đến máy chủ. Vui lòng kiểm tra kết nối internet.";
						} else if (error.message) {
							errorMessage = error.message;
						}
						
						Swal.fire({
							icon: "error",
							title: "Lỗi!",
							customClass: {
								confirmButton: "my-custom-button",
							},
							text: errorMessage,
						});
					});
			}
		});
	}
});
