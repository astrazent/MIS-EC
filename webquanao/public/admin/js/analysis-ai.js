/**
 * Script xử lý chức năng phân tích AI trên trang quản trị
 */
$(document).ready(function () {
	// Xử lý sự kiện click cho tất cả các nút phân tích AI
	$(".btn-analyze-ai").on("click", function () {
		// Tìm panel kết quả gần nhất
		var resultPanel = $(this).closest(".row").find(".analysis-result-panel");

		// Hiển thị loading
		resultPanel.show();
		resultPanel.find(".loading-analysis").show();
		resultPanel.find(".analysis-result").hide();

		// Thay đổi tiêu đề phân tích
		resultPanel
			.find(".panel-title")
			.text("Phân tích tình hình kinh doanh và đề xuất chiến lược");

		// Thu thập dữ liệu từ panel gần nhất để phân tích
		var productData = {
			recent_sales: true, // Yêu cầu phân tích doanh số gần đây
			customer_behavior: true, // Yêu cầu phân tích hành vi khách hàng
			market_trends: true, // Yêu cầu phân tích xu hướng thị trường
		};

		// Thu thập dữ liệu từ các bảng gần nhất
		var nearestPanel = $(this).closest(".row").prev().find(".panel");
		if (nearestPanel.length > 0) {
			// Lấy tiêu đề panel để xác định loại dữ liệu
			var panelTitle = nearestPanel.find(".panel-heading").text().trim();
			productData.panel_title = panelTitle;

			// Thu thập dữ liệu từ bảng nếu có
			var tableData = [];
			nearestPanel.find("table tbody tr").each(function () {
				var rowData = {};
				$(this)
					.find("td")
					.each(function (index) {
						var headerText = $(this)
							.closest("table")
							.find("th")
							.eq(index)
							.text()
							.trim();
						rowData[headerText] = $(this).text().trim();
					});
				tableData.push(rowData);
			});

			if (tableData.length > 0) {
				productData.table_data = tableData;
			}
		}

		// Gọi API thông qua proxy PHP thay vì trực tiếp đến container openAI
		fetch("/proxy/analyze_proxy.php", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(productData),
		})
			.then((response) => {
				// Kiểm tra nếu response không phải là JSON
				const contentType = response.headers.get("content-type");
				if (!contentType || !contentType.includes("application/json")) {
					throw new Error(
						"Phản hồi không phải định dạng JSON. Có thể server đang gặp sự cố."
					);
				}
				return response.json();
			})
			.then((response) => {
				// Ẩn loading và hiển thị kết quả
				resultPanel.find(".loading-analysis").hide();
				resultPanel.find(".analysis-result").show();

				if (response.status === "success") {
					// Hiển thị kết quả phân tích từ API
					resultPanel.find(".analysis-result").html(response.data.analysis);
				} else {
					// Hiển thị thông báo lỗi
					resultPanel
						.find(".analysis-result")
						.html(
							"<div class='alert alert-danger'>Lỗi: " +
								(response.message || "Không thể phân tích dữ liệu") +
								"</div>"
						);
				}
			})
			.catch((error) => {
				// Ẩn loading và hiển thị thông báo lỗi
				resultPanel.find(".loading-analysis").hide();
				resultPanel.find(".analysis-result").show();

				// Tạo thông báo lỗi chi tiết và dễ hiểu hơn
				let errorMessage = "Lỗi kết nối đến service AI";

				// Kiểm tra loại lỗi để hiển thị thông báo phù hợp
				if (error.message && error.message.includes("JSON")) {
					errorMessage = "Lỗi định dạng dữ liệu: " + error.message;
				} else if (error.message && error.message.includes("NetworkError")) {
					errorMessage =
						"Lỗi kết nối mạng: Không thể kết nối đến máy chủ phân tích";
				} else if (error.message && error.message.includes("Failed to fetch")) {
					errorMessage =
						"Không thể kết nối đến máy chủ phân tích AI. Vui lòng kiểm tra kết nối mạng và thử lại sau.";
				} else if (error.message) {
					errorMessage = error.message;
				}

				resultPanel
					.find(".analysis-result")
					.html("<div class='alert alert-danger'>" + errorMessage + "</div>");
				console.error("Lỗi khi gọi phân tích AI:", error);

				// Ghi log chi tiết hơn để debug
				console.debug("Chi tiết lỗi:", {
					message: error.message,
					stack: error.stack,
					name: error.name,
				});
			});
	});
});
