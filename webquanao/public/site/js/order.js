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
		} else {
			resetShipping();
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
		} else {
			resetShipping();
		}
	};
}

// Bắt sự kiện chọn phường xã để tính tiền vận chuyển
// Lấy toạ độ
function matchWords(inputStr, targetStr) {
	const inputWords = inputStr.toLowerCase().split(/\s+/);
	const targetWords = targetStr.toLowerCase().split(/\s+/);

	return inputWords.every((word) => targetWords.includes(word));
}

function checkMatchInList(inputStr, list) {
	for (let i = 0; i < list.length; i++) {
		if (matchWords(inputStr, list[i].properties.region)) {
			return list[i].geometry.coordinates; // Dừng duyệt nếu match
		}
	}
	return null; // Không có phần tử nào match
}

function getLastTwoWords(str) {
	const words = str.trim().split(/\s+/);
	return words.slice(-2).join(" ");
}

async function getCoordinates(city, district, ward) {
	const el = document.getElementById("openroute");
	const apiKey = el.getAttribute("data-key");
	const location = `${ward}, ${district}, ${city}, Vietnam`;

	const url = `https://api.openrouteservice.org/geocode/search?api_key=${apiKey}&text=${encodeURIComponent(
		location
	)}`;

	try {
		const response = await fetch(url);
		const data = await response.json();
		let coordinates = null;
		if (data.features && data.features.length > 0) {
			coordinates = checkMatchInList(getLastTwoWords(city), data.features);
			let longitude;
			let latitude;
			if (coordinates != null) {
				longitude = coordinates[0]; // Kinh độ
				latitude = coordinates[1]; // Vĩ độ
			} else {
				return false;
			}
			return { longitude, latitude };
		} else {
			throw new Error("Không tìm thấy tọa độ cho địa điểm này.");
		}
	} catch (error) {
		console.error("Lỗi khi lấy tọa độ:", error);
	}
}

function toRadians(degrees) {
	return (degrees * Math.PI) / 180;
}

function haversine(coord1, coord2) {
	const R = 6371; // Bán kính Trái Đất (km)

	const [lon1, lat1] = coord1;
	const [lon2, lat2] = coord2;

	const φ1 = toRadians(lat1);
	const φ2 = toRadians(lat2);
	const Δφ = toRadians(lat2 - lat1);
	const Δλ = toRadians(lon2 - lon1);

	const a =
		Math.sin(Δφ / 2) ** 2 + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) ** 2;

	const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

	const distance = R * c;
	return distance * 1000; // Trả về đơn vị m
}
function resetShipping() {
	document.querySelector(".shipping-detail").style.display = "none";
	document.querySelector(".shipping").style.display = "none";
}

async function getDistance(fromCoords, toCoords) {
	const url = "https://api.openrouteservice.org/v2/directions/driving-car";
	const el = document.getElementById("openroute");
	const apiKey = el.getAttribute("data-key");
	const body = {
		coordinates: [fromCoords, toCoords],
	};

	const response = await fetch(url, {
		method: "POST",
		headers: {
			Authorization: apiKey,
			"Content-Type": "application/json",
		},
		body: JSON.stringify(body),
	});

	const data = await response.json();
	if (data.routes && data.routes.length > 0) {
		const distance = data.routes[0].summary.distance; // đơn vị: mét
		const duration = data.routes[0].summary.duration; // đơn vị: giây
		return { distance, duration };
	} else if (data.error.message != null) {
		const distance = haversine(fromCoords, toCoords); // đơn vị: mét
		const duration = undefined; // đơn vị: giây
		return { distance, duration };
	} else {
		throw new Error("Không thể tính khoảng cách.");
	}
}

// đổi đơn vị tiền
function formatCurrency(number) {
	if (isNaN(number)) {
		return "0 VND";
	}

	return Number(number).toLocaleString("vi-VN") + " VND";
}

wards.addEventListener("change", function () {
	cityText = citis.options[citis.selectedIndex].text;
	districtText = district.options[district.selectedIndex].text;
	wardText = ward.options[ward.selectedIndex].text;

	if (!cityText || !districtText || !wardText) {
		resetShipping();
		return;
	}

	// HV bưu chính viễn thông - ngọc trực
	let from = [105.7684188, 20.9847744];
	let to = [];
	if (this.value) {
		getCoordinates(cityText, districtText, wardText)
			.then((tocoords) => {
				if (tocoords) {
					to.push(tocoords.longitude);
					to.push(tocoords.latitude);
					return getDistance(from, to);
				} else {
					document.querySelector(".shipping-detail").style.display = "block";
					document.querySelector(".distance").textContent = `Chưa xác định`;
					document.querySelector(".fee").textContent = `Thông báo sau`;
					return Promise.reject("Không tìm thấy tọa độ đích.");
				}
			})
			.then(async (result) => {
				// 1. Hiển thị div với class 'shipping-detail' dưới dạng block
				document.querySelector(".shipping-detail").style.display = "block";
				// 2. Thay đổi nội dung của distance và fee
				const itemprice = document.getElementById("totalPrice").dataset.price;
				const shipfee = await getShippingFee(Math.round(result.distance/1000), Number(itemprice));
				const total = Math.round(Number(shipfee) + Number(itemprice));
				document.querySelector(".distance").textContent = `${Math.round(
					result.distance / 1000
				)} km`;
				document.querySelector(".fee").textContent = `${formatCurrency(
					shipfee
				)}`;

				// Hiển thị dòng phí ship
				document.querySelector(".shipping").style.display = "flex";
				document.getElementById("shippingFee").textContent = `${formatCurrency(
					shipfee
				)}`;
				document.getElementById("totalPrice").textContent = `${formatCurrency(
					total
				)}`;
			})
			.catch((err) => {
				console.error("Lỗi:", err);
			});
	}
});

async function getShippingFee(distance, totalAmount) {
    try {
        const response = await fetch("http://localhost:8080/shipping-fee", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            }
        });

        const text = await response.text();
        const data = JSON.parse(text);

        if (data.status !== "success") {
            console.error("Lỗi lấy dữ liệu shipping rules");
            return null;
        }

        const rules = data.data;
        let selectedRule = null;

        for (const rule of rules) {
            const minDist = parseFloat(rule.min_distance_km);
            const maxDist = parseFloat(rule.max_distance_km);
            const minAmount = parseFloat(rule.min_order_amount);
            const maxAmount = parseFloat(rule.max_order_amount);

            const matchDistance =
                (isNaN(minDist) || distance >= minDist) &&
                (isNaN(maxDist) || distance <= maxDist);

            const matchAmount =
                (isNaN(minAmount) || totalAmount >= minAmount) &&
                (isNaN(maxAmount) || totalAmount <= maxAmount);

            if (matchDistance && matchAmount) {
                selectedRule = rule;
                break;
            }
        }

        if (selectedRule) {
            let fee = parseFloat(selectedRule.shipping_fee);
            if (selectedRule.unit === "MUL") {
                fee = fee * distance;
            }
            return fee;
        } else {
            return null; // hoặc 1 giá trị mặc định như 50000
        }
    } catch (error) {
        console.error("Lỗi fetch:", error);
        return null;
    }
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
