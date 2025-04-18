// Logic chọn địa chỉ
var citis = document.getElementById("city");
var districts = document.getElementById("district");
var wards = document.getElementById("ward");

const defaultCity = citis.dataset.default ?? "";
const defaultDistrict = districts.dataset.default ?? "";
const defaultWard = wards.dataset.default ?? "";

var Parameter = {
	url: "http://localhost:8080/api/read-json",
	method: "GET",
	responseType: "application/json",
};
var promise = axios(Parameter);
promise.then(function (result) {
	renderCity(result.data);

	// Điền thông tin sau khi dữ liệu được load
	for (let i = 0; i < citis.options.length; i++) {
		if (citis.options[i].text === defaultCity) {
			citis.selectedIndex = i;
			break;
		}
	}

	// Gửi sự kiện onchange
	let eventCity = new Event("change");
	citis.dispatchEvent(eventCity);

	for (let i = 0; i < districts.options.length; i++) {
		if (districts.options[i].text === defaultDistrict) {
			districts.selectedIndex = i;
			break;
		}
	}

	// Gửi sự kiện onchange
	let eventDistrict = new Event("change");
	districts.dispatchEvent(eventDistrict);

	for (let i = 0; i < wards.options.length; i++) {
		if (wards.options[i].text === defaultWard) {
			wards.selectedIndex = i;
			break;
		}
	}

	// Gửi sự kiện onchange
	let eventWard = new Event("change");
	wards.dispatchEvent(eventWard);
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
function editContent(button, id) {
	// Tìm button delete gần nhất trước button edit này
	const closestDeleteBtn = button.previousElementSibling;
	let dontFetch = false;

	// Kiểm tra nếu đó thực sự là button delete
	if (closestDeleteBtn && closestDeleteBtn.classList.contains("btn-delete")) {
		// Tìm thẻ icon trash trong button delete
		const trashIcon = closestDeleteBtn.querySelector("i.fa-trash");

		if (trashIcon) {
			// Tạo icon mới
			const xmarkIcon = document.createElement("i");
			xmarkIcon.className = "fa-solid fa-xmark";

			// Thay thế icon
			trashIcon.replaceWith(xmarkIcon);
		}
	}

	var contentDiv = document.getElementById(id);
	var currentText;
	if (id === "address-number") {
		currentText = document
			.getElementById("address")
			.innerText.split(",")[0]
			.trim();
	} else {
		currentText =
			contentDiv.textContent == undefined ? "" : contentDiv.textContent;
	}

	var inputField = document.createElement("input");
	inputField.type = "text";
	inputField.value = currentText;
	inputField.style.width = "95%";
	inputField.classList.add("field", "border", "border-gray-300");

	contentDiv.innerHTML = "";
	contentDiv.appendChild(inputField);

	let hiscontent = inputField.value;
	if (id === "email") {
		var contentDiv2 = document.getElementById("password");
		var inputField2 = document.createElement("input");
		inputField2.type = "password";
		inputField2.value = "";
		inputField2.style.width = "95%";
		inputField2.classList.add("field", "border", "border-gray-300");

		contentDiv2.innerHTML = "";
		contentDiv2.appendChild(inputField2);

		// Hiển thị trường password nếu là email
		const infoPassword = document.querySelector(".password");
		infoPassword.style.display = "block";
	}

	if (id === "address-number") {
		// Hiển thị các trường phụ nếu là address
		const infoRow = document.querySelector(".address-info");
		const infoDetail = document.querySelector(".address-number");
		infoRow.style.display = "flex";
		infoDetail.style.display = "block";
	}

	// Đổi icon thành check
	button.innerHTML = '<i class="fa-solid fa-check"></i>';

	// Xử lý khi bấm lưu
	button.onclick = function () {
		const newText = inputField.value;
		let field = document.querySelector(".field");
		let isValid = true;

		// Kiểm tra trường điền
		if (newText === "") {
			showError(field, "Vui lòng điền đầy đủ thông tin");
			isValid = false;
		} else {
			clearError(field);
		}

		// Nếu có lỗi, không gửi API
		if (!isValid) return;

		const dataToSend = {
			id: id,
			value: newText,
		};

		if (id === "email") {
			// Kiểm tra Email
			let emailInput = document.querySelector("#email .field");
			let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (!emailRegex.test(emailInput.value.trim())) {
				showError(emailInput, "Email không hợp lệ");
				isValid = false;
			} else {
				clearError(emailInput);
			}

			//Kiểm tra trường điền mật khẩu 
			console.log(field);
			console.log(field.nextElementSibling);
			return;
			if (newText === "") {
				showError(field, "Vui lòng điền đầy đủ thông tin");
				isValid = false;
			} else {
				clearError(field);
			}

			// Nếu có lỗi, không gửi API
			if (!isValid) return;
		}

		if (id === "address-number") {
			// 🏠 Xử lý riêng cho địa chỉ
			const citySelect = document.getElementById("city");
			const districtSelect = document.getElementById("district");
			const wardSelect = document.getElementById("ward");

			const city = citySelect.options[citySelect.selectedIndex].text;
			const district =
				districtSelect.options[districtSelect.selectedIndex].text;
			const ward = wardSelect.options[wardSelect.selectedIndex].text;

			// Kiểm tra Tỉnh/Thành
			if (city === "Trống") {
				showError(citySelect, "Vui lòng chọn Tỉnh/Thành");
				isValid = false;
			} else {
				clearError(citySelect);
			}

			// Kiểm tra Quận/Huyện
			if (district === "Trống") {
				showError(districtSelect, "Vui lòng chọn Quận/Huyện");
				isValid = false;
			} else {
				clearError(districtSelect);
			}

			// Kiểm tra Phường/Xã
			if (ward === "Trống") {
				showError(wardSelect, "Vui lòng chọn Phường/Xã");
				isValid = false;
			} else {
				clearError(wardSelect);
			}

			// Nếu có lỗi, không gửi API
			if (!isValid) return;

			// Gán vào object gửi đi nếu cần
			dataToSend.province = city === "Trống" ? null : city;
			dataToSend.district = district === "Trống" ? null : district;
			dataToSend.ward = ward === "Trống" ? null : ward;

			// Lọc và ghép chuỗi
			const addressParts = [newText, ward, district, city].filter(
				(part) => part && part !== "Trống"
			);

			document.getElementById("address").innerHTML = addressParts.join(", ");

			const infoRows = document.querySelectorAll(".address-edit");
			infoRows.forEach(function (row) {
				row.style.display = "none";
			});
		} else if (id === "email") {
			// Kiểm tra mật khẩu trước
			let passwordData = {
				password: inputField2.value.trim(),
			};
			fetch("http://localhost:8080/check-password", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify(passwordData),
			})
				.then((response) => response.json())
				.then((data) => {
					if (data.status == "success") {
						console.log("Xác nhận mật khẩu thành công:", data);
						contentDiv.innerHTML = newText;
						// Hiển thị trường password nếu là email
						const infoPassword = document.querySelector(".password");
						infoPassword.style.display = "none";
					} else {
						console.log("Xác nhận mật khẩu thất bại:", data);
						contentDiv.innerHTML = hiscontent;
						dontFetch = true;
					}
				})
				.catch((error) => console.error("Lỗi khi cập nhật:", error));
		} else {
			// ✏️ Xử lý các trường khác
			contentDiv.innerHTML = newText;
		}

		if (!dontFetch) {
			// Gửi dữ liệu cập nhật lên server
			fetch("http://localhost:8080/update-info", {
				method: "PUT",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify(dataToSend),
			})
				.then((response) => response.json())
				.then((data) => console.log("Cập nhật thành công:", data))
				.catch((error) => console.error("Lỗi khi cập nhật:", error));
		}

		// Đổi lại icon thành bút
		button.innerHTML = '<i class="fa-solid fa-pen"></i>';

		// Đổi lại icon cancel
		// Kiểm tra nếu đó thực sự là button delete
		if (closestDeleteBtn && closestDeleteBtn.classList.contains("btn-delete")) {
			// Tìm thẻ icon xmark trong button delete
			const xmarkIcon = closestDeleteBtn.querySelector("i.fa-xmark");

			if (xmarkIcon) {
				// Tạo icon trash mới
				const trashIcon = document.createElement("i");
				trashIcon.className = "fa-solid fa-trash";

				// Thay thế icon
				xmarkIcon.replaceWith(trashIcon);
			}
		}

		button.onclick = function () {
			editContent(button, id);
		};
	};
}

// Xử lý xác nhận xóa (chỉ xóa nội dung của phần tử có class 'content')
document.addEventListener("DOMContentLoaded", function () {
	document.querySelectorAll(".btn-delete").forEach((button) => {
		button.addEventListener("click", function () {
			const icon = button.querySelector("i.fa-xmark");

			if (icon) {
				if (
					icon.classList.contains("fa-solid") &&
					icon.classList.contains("fa-xmark")
				) {
					// Chuyển edit về trạng thái ban đầu
					const container = button.closest(".info-buttons"); // Thay '.container' bằng class cha chung
					const editButton = container.querySelector(".btn-edit");
					const infoLabel = editButton.parentElement.parentElement;
					const textInput = infoLabel.querySelector('input[type="text"]').value;
					const idInfo = infoLabel.querySelector(".content").id;
					editButton.innerHTML = '<i class="fa-solid fa-pen"></i>';

					var contentDiv = document.getElementById(idInfo);
					contentDiv.innerHTML = textInput;

					// Hủy sự kiện click, tạo sự kiện mới cho edit
					editButton.onclick = null;
					editButton.onclick = function () {
						editContent(this, idInfo);
					};

					// Cập nhật lại icon
					button.innerHTML = '<i class="fa-solid fa-trash"></i>';
				}
				return;
			}

			let row = this.closest(".info-row");
			let confirmRow = row.nextElementSibling;

			if (!confirmRow || !confirmRow.classList.contains("confirm-delete")) {
				let newConfirmRow = document.createElement("div");
				newConfirmRow.classList.add("info-row", "confirm-delete");
				newConfirmRow.style.display = "flex";
				newConfirmRow.style.justifyContent = "center";
				newConfirmRow.style.alignItems = "center";

				let buttonContainer = document.createElement("div");
				buttonContainer.style.display = "flex";
				buttonContainer.style.gap = "10px";

				buttonContainer.innerHTML = `
                    <div class="info-label">Bạn có muốn xoá không?</div>
                    <button class="btn btn-confirm" style="background-color: #4CAF50; color: white;">
                        <i class="fa-solid fa-check"></i>
                    </button>
                    <button class="btn btn-cancel" style="background-color: #f44336; color: white;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                `;

				newConfirmRow.appendChild(buttonContainer);
				row.after(newConfirmRow);

				// Khi bấm xác nhận xóa (chỉ xóa nội dung của phần tử có class 'content')
				newConfirmRow
					.querySelector(".btn-confirm")
					.addEventListener("click", function () {
						fetch("http://localhost:8080/delete-info", {
							method: "DELETE",
							headers: {
								"Content-Type": "application/json",
							},
							body: JSON.stringify({
								id: row.dataset.id,
							}),
						})
							.then((response) => response.json())
							.then((data) => {
								console.log("Xóa thành công:", data);

								// Chỉ xóa nội dung của các phần tử có class 'content'
								row.querySelectorAll(".content").forEach((contentElement) => {
									contentElement.innerHTML = "";
									contentElement.textContent = "(Trống)";
								});

								// Xóa dòng xác nhận
								newConfirmRow.remove();
							})
							.catch((error) => console.error("Lỗi khi xóa:", error));
					});

				// Khi bấm hủy xóa
				newConfirmRow
					.querySelector(".btn-cancel")
					.addEventListener("click", function () {
						newConfirmRow.remove();
					});
			} else {
				confirmRow.remove();
			}
		});
	});
});
document
	.getElementById("changepassword")
	.addEventListener("click", function () {
		window.location.href = "http://localhost:8080/doi-mat-khau";
	});
