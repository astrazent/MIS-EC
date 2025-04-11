<link
	rel="stylesheet"
	href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
<style>
	h2 {
		text-align: center;
	}

	.info-row {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 10px 0;
		border-bottom: 1px solid #ddd;
	}

	.info-row:last-child {
		border-bottom: none;
	}

	.info-label {
		display: flex;
		justify-content: space-between;
		width: 100%;
	}

	.info-label .label {
		font-weight: bold;
		flex: 0 0 150px;
		/* Cố định chiều rộng cho phần nhãn */
	}

	.info-label .content {
		flex: 1;
		/* Phần nội dung chiếm phần còn lại */
	}

	.label {
		color: black;
		text-align: left;
		font-size: 100%;
	}

	.info-buttons {
		display: flex;
		gap: 10px;
	}

	.btn {
		padding: 5px 10px;
		border: none;
		border-radius: 3px;
		cursor: pointer;
	}

	.btn-edit {
		background-color: #4caf50;
		color: white;
	}

	.btn-delete {
		background-color: #f44336;
		color: white;
	}

	input[type="text"] {
		border: 1px solid #ddd;
		padding: 5px;
	}

	.col-md-6 {
		width: 100%;
	}

	.info-row {
		padding-right: 10px;

	}

	.btn-edit {
		width: 32px;
		height: 32px;
	}

	.fa-solid {
		font-size: smaller;
	}

	.changepassword {
		display: inline-block;
		padding: 10px 20px;
		background-color: #007bff;
		/* Màu xanh dương */
		color: white;
		border: none;
		border-radius: 8px;
		font-size: 16px;
		text-align: center;
		cursor: pointer;
		margin-top: 30px;
		transition: background-color 0.3s ease;
	}
</style>

<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 clearpaddingr">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url(); ?>#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Trang chủ</a></li>
			<li class="active">Thông tin tài khoản</li>
		</ol>
		<div class="col-md-6 clearpadding">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Thông tin tài khoản</h3>
				</div>
				<div class="info-row">
					<div class="info-label">
						<div class="label">Họ Tên:</div>
						<div class="content" id="name">Nguyễn Văn A</div>
					</div>
					<div class="info-buttons">
						<button
							class="btn btn-edit"
							onclick="editContent(this, 'name')">
							<i class="fa-solid fa-pen"></i>
						</button>
						<button class="btn btn-delete">
							<i class="fa-solid fa-trash"></i>
						</button>
					</div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<div class="label">Số Điện Thoại:</div>
						<div class="content" id="phone">0123456789</div>
					</div>
					<div class="info-buttons">
						<button
							class="btn btn-edit"
							onclick="editContent(this, 'phone')">
							<i class="fa-solid fa-pen"></i>
						</button>
						<button class="btn btn-delete">
							<i class="fa-solid fa-trash"></i>
						</button>
					</div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<div class="label">Email:</div>
						<div class="content" id="email">nguyenvana@example.com</div>
					</div>
					<div class="info-buttons">
						<button
							class="btn btn-edit"
							onclick="editContent(this, 'email')">
							<i class="fa-solid fa-pen"></i>
						</button>
						<button class="btn btn-delete">
							<i class="fa-solid fa-trash"></i>
						</button>
					</div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<div class="label">Địa Chỉ:</div>
						<div class="content" id="address">
							123 Đường ABC, Quận 1, TP.HCM
						</div>
					</div>
					<div class="info-buttons">
						<button
							class="btn btn-edit"
							onclick="editContent(this, 'address')">
							<i class="fa-solid fa-pen"></i>
						</button>
						<button class="btn btn-delete">
							<i class="fa-solid fa-trash"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div style="text-align: center;">
			<button class="changepassword" id="changepassword">Đổi mật khẩu</button>
		</div>

	</div>
</div>

<script>
	// Hàm chỉnh sửa thông tin
	function editContent(button, id) {
		var contentDiv = document.getElementById(id);
		var currentText = contentDiv.innerText;

		var inputField = document.createElement("input");
		inputField.type = "text";
		inputField.value = currentText;
		inputField.style.width = "95%";

		contentDiv.innerHTML = "";
		contentDiv.appendChild(inputField);

		// Đổi icon thành check khi đang sửa
		button.innerHTML = '<i class="fa-solid fa-check"></i>';

		button.onclick = function() {
			var newText = inputField.value;
			contentDiv.innerHTML = newText;

			// Fetch API cập nhật dữ liệu
			fetch("http://localhost:8080/api/update-info", {
					method: "POST",
					headers: {
						"Content-Type": "application/json"
					},
					body: JSON.stringify({
						id: id,
						value: newText
					})
				})
				.then(response => response.json())
				.then(data => console.log("Cập nhật thành công:", data))
				.catch(error => console.error("Lỗi khi cập nhật:", error));

			// Đổi lại icon thành pen
			button.innerHTML = '<i class="fa-solid fa-pen"></i>';
			button.onclick = function() {
				editContent(button, id);
			};
		};
	}

	// Xử lý xác nhận xóa
	document.addEventListener("DOMContentLoaded", function() {
		document.querySelectorAll(".btn-delete").forEach((button) => {
			button.addEventListener("click", function() {
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

					// Khi bấm xác nhận xóa
					newConfirmRow.querySelector(".btn-confirm").addEventListener("click", function() {
						fetch("http://localhost:8080/api/delete-info", {
								method: "DELETE",
								headers: {
									"Content-Type": "application/json"
								},
								body: JSON.stringify({
									id: row.dataset.id
								})
							})
							.then(response => response.json())
							.then(data => {
								console.log("Xóa thành công:", data);
								row.remove();
								newConfirmRow.remove();
							})
							.catch(error => console.error("Lỗi khi xóa:", error));
					});

					// Khi bấm hủy xóa
					newConfirmRow.querySelector(".btn-cancel").addEventListener("click", function() {
						newConfirmRow.remove();
					});
				} else {
					confirmRow.remove();
				}
			});
		});
	});
	document.getElementById('changepassword').addEventListener('click', function() {
		window.location.href = 'http://localhost:8080/doi-mat-khau';
	});
</script>