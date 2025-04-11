// Logic chọn địa chỉ
var citis = document.getElementById("city");
var districts = document.getElementById("district");
var wards = document.getElementById("ward");
// Lấy thẻ chứa thông tin người dùng
var userInfoElement = document.getElementById("userInfo");
var userData = JSON.parse(userInfoElement.getAttribute("data-user"));

// Điền thông tin vào các trường input
document.getElementById("name").value = userData.name;
document.getElementById("email").value = userData.email;
document.getElementById("phone").value = userData.phone;
document.getElementById("address").value = userData.address;

var Parameter = {
	url: "http://localhost:8080/api/read-json",
	method: "GET",
	responseType: "application/json",
};
function selectOptionByText(selectId, textToFind) {
	let select = document.getElementById(selectId);
	for (let i = 0; i < select.options.length; i++) {
		if (select.options[i].text === textToFind) {
			select.selectedIndex = i;
			break;
		}
	}
}
var promise = axios(Parameter);
promise.then(function (result) {
	renderCity(result.data);

	// Điền thông tin sau khi dữ liệu được load
	let cityElement = document.getElementById("city");

	for (let i = 0; i < cityElement.options.length; i++) {
		if (cityElement.options[i].text === userData.city) {
			cityElement.selectedIndex = i;
			break;
		}
	}

	// Gửi sự kiện onchange
	let eventCity = new Event("change");
	cityElement.dispatchEvent(eventCity);

	let districtElement = document.getElementById("district");
	for (let i = 0; i < districtElement.options.length; i++) {
		if (districtElement.options[i].text === userData.district) {
			districtElement.selectedIndex = i;
			break;
		}
	}

	// Gửi sự kiện onchange
	let eventDistrict = new Event("change");
	districtElement.dispatchEvent(eventDistrict);

	// ====== Dành cho WARD ======
	let wardElement = document.getElementById("ward");
	for (let i = 0; i < wardElement.options.length; i++) {
		if (wardElement.options[i].text === userData.ward) {
			wardElement.selectedIndex = i;
			break;
		}
	}

	// Gửi sự kiện onchange
	let eventWard = new Event("change");
	wardElement.dispatchEvent(eventWard);
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
	document
		.getElementById("submitBtn")
		.addEventListener("click", function (event) {
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

			let selectedPayment = document.querySelector(
				'input[name="payment"]:checked'
			);
			let paymentError = document.getElementById("payment-error");
			if (!selectedPayment) {
				paymentError.classList.remove("hidden"); // Hiển thị cảnh báo
				isValid = false;
			} else {
				paymentError.classList.add("hidden"); // Ẩn cảnh báo nếu đã chọn
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

			if (
				selectedPayment.value == "cash" ||
				selectedPayment.value == "vietqr" ||
				selectedPayment.value == "pos"
			) {
				// Gửi dữ liệu lên server qua fetch API (Fake API endpoint)
				fetch("http://localhost:8080/order/complete", {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: JSON.stringify(formData),
				})
					.then((response) => response.text())
					.then((text) => {
						console.log("Raw response:", text); // Log dữ liệu để kiểm tra
						return JSON.parse(text); // Chuyển thành JSON thủ công
					})
					.then((data) => {
						// Hiển thị popup đặt hàng thành công
						if (data.status == "null_user") {
							window.location.href = "/dang-nhap";
						} else if (data.status == "success") {
							Swal.fire({
								icon: "success",
								title: "Đặt hàng thành công!",
								text: data.message,
								customClass: {
									confirmButton: "my-custom-button",
								},
								confirmButtonText: "OK",
							}).then(() => {
								window.location.href = "/";
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
			} else if (selectedPayment.value == "vnpay") {
				fetch("http://localhost:8080/vnpay/payment", {
					method: "POST",
					headers: { "Content-Type": "application/json" },
					body: JSON.stringify(formData),
				})
					.then((response) => response.json())
					.then((data) => {
						if (data.status == "error") {
							window.location.href = "/dang-nhap";
						}
						if (data.payment_url) {
							// Chuyển hướng người dùng đến VNPAY
							window.location.href = data.payment_url;
						}
					})
					.catch((error) => console.error("Error:", error));
			}
		});
});
