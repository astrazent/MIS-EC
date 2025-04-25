import random
import time
from faker import Faker

fake = Faker('vi_VN')
Faker.seed(42)
random.seed(42)

# Tạo dữ liệu cho bảng comments
comments = []
comment_templates = [
    "Sản phẩm rất đẹp, chất lượng tốt.",
    "Chất lượng sản phẩm tốt, giá cả hợp lý.",
    "Sản phẩm không đẹp lắm.",
    "Rất hài lòng với sản phẩm.",
    "Sẽ mua lại lần nữa, rất đáng tiền.",
    "Giao hàng nhanh, sản phẩm đúng mô tả.",
    "Chất liệu vải tốt, kiểu dáng đẹp.",
    "Màu sắc đẹp, form chuẩn.",
    "Tạm ổn, sản phẩm đúng như hình.",
    "Không quá nổi bật nhưng chất lượng ổn.",
    "Form vừa vặn, mặc rất thoải mái.",
    "Thiết kế đơn giản nhưng tinh tế.",
    "Đẹp hơn mong đợi, vải mịn mát.",
    "Giá hợp lý so với chất lượng.",
    "Vải hơi mỏng nhưng vẫn ổn.",
    "Kiểu dáng trẻ trung, năng động.",
    "Màu hơi khác hình một chút nhưng vẫn đẹp.",
    "Mặc lên rất sang, đường may chắc chắn.",
    "Hơi rộng so với size nhưng đổi trả dễ.",
    "Vải không nhăn, rất thích.",
    "Phù hợp để mặc đi chơi, đi làm.",
    "Sản phẩm đúng mô tả, gói hàng cẩn thận.",
    "Shop tư vấn nhiệt tình, giao hàng nhanh.",
    "Thiết kế hợp trend, mặc lên đẹp.",
    "Rất đáng tiền, sẽ giới thiệu bạn bè.",
    "Form hơi bó nhưng ôm dáng đẹp.",
    "Vải đẹp, không bị xù lông khi giặt.",
    "Đường may tỉ mỉ, không bị bung chỉ.",
    "Sản phẩm không giống hình cho lắm.",
    "Chất lượng ổn, giao đúng size đặt.",
    "Không quá ấn tượng, nhưng dùng được.",
    "Rất vừa ý, đúng như mong đợi.",
    "Lần đầu mua mà rất hài lòng.",
    "Không thích chất liệu vải lắm.",
    "Mặc thoáng mát, không bị nóng.",
    "Sản phẩm như hình, đóng gói kỹ.",
    "Shop đóng gói đẹp, có cả túi đựng.",
    "Áo hơi ngắn so với hình.",
    "Dễ phối đồ, mặc được nhiều dịp.",
    "Đúng form, vừa vặn với chiều cao cân nặng.",
    "Giao hàng hơi chậm nhưng sản phẩm tốt.",
    "Không bị ra màu khi giặt.",
    "Chất vải mềm, mặc thoải mái cả ngày.",
    "Sản phẩm không được như mong đợi.",
    "Đẹp quá trời, đáng từng đồng.",
    "Size chuẩn, mặc rất vừa.",
    "Sản phẩm phù hợp giá tiền.",
    "Rất đẹp, sẽ mua thêm mẫu khác.",
    "Mặc rất tôn dáng.",
    "Áo váy mềm mịn, dễ chịu.",
    "Không bị nhăn sau khi giặt.",
    "Màu rất tươi, mặc lên sáng da.",
    "Áo mặc thoải mái, vải thấm hút tốt.",
    "Dễ phối với quần jeans, chân váy.",
    "Không hài lòng lắm, vải hơi nóng.",
    "Form không đẹp như tưởng tượng.",
    "Đặt size M mà giao size L.",
    "Mặc lên nhìn trẻ trung hơn tuổi.",
    "Đúng mô tả, giao hàng nhanh.",
    "Áo dễ bị xù lông sau 2 lần giặt.",
    "Sản phẩm đẹp hơn trong ảnh.",
    "Shop uy tín, mua nhiều lần rồi.",
    "Sẽ quay lại mua tiếp.",
    "Không phù hợp với mình lắm.",
    "Giao nhầm màu nhưng shop xử lý nhanh.",
    "Vải co giãn tốt, mặc cả ngày thoải mái.",
    "Không bị bai nhão khi giặt.",
    "Cổ áo hơi chật, cần điều chỉnh size.",
    "Sản phẩm tốt, đóng gói đẹp.",
    "Giá rẻ mà chất lượng ổn.",
    "Áo rất nhẹ, phù hợp mùa hè.",
    "Đóng gói cẩn thận, không bị nhăn.",
    "Tay áo may hơi ẩu.",
    "Màu đẹp, không bị phai.",
    "Kiểu dáng hợp trend.",
    "Hơi thất vọng vì form áo không đẹp.",
    "Shop làm việc chuyên nghiệp.",
    "Không bị co rút sau khi giặt máy.",
    "Vải mịn, mát, không ngứa.",
    "Áo mặc lên body đẹp hơn tưởng tượng.",
    "Sản phẩm đáng đồng tiền.",
    "Giao hàng nhanh, sản phẩm ok.",
    "Mặc đi làm, đi chơi đều phù hợp.",
    "Form chuẩn, đúng mô tả.",
    "Áo khá bền, giặt máy không sao.",
    "Vải dày dặn, mặc vào mùa lạnh ổn.",
    "Áo mặc lên form rất đẹp.",
    "Giặt không ra màu, điểm cộng lớn.",
    "Size chuẩn, chất vải tốt.",
    "Mua lần 2 vẫn rất ưng.",
    "Áo đẹp, vải mịn.",
    "Rất thích sản phẩm này.",
    "Sản phẩm giao thiếu phụ kiện.",
    "Không đẹp như tưởng tượng.",
    "Shop phản hồi nhanh, hỗ trợ tốt.",
    "Vừa túi tiền, chất lượng ổn.",
    "Đổi hàng rất dễ, shop hỗ trợ tốt.",
    "Sản phẩm rất đáng yêu.",
    "Áo giữ form tốt, không nhăn.",
    "Thiết kế basic, dễ phối đồ.",
    "Form áo thon gọn, tôn dáng.",
    "Không thích kiểu dáng lắm.",
    "Đóng gói đẹp, cẩn thận.",
    "Sản phẩm đúng như miêu tả.",
    "Áo nhẹ, dễ mặc mùa hè.",
    "Vải mềm, dễ chịu với da nhạy cảm.",
    "Shop phục vụ tốt, sản phẩm chất lượng."
]


created_time = int(time.time())

# Mỗi product_id có 3 đến 5 comment từ user_id khác nhau
product_ids = list(range(1, 25))  # 25 product_id
user_ids = list(range(4, 53))     # 50 user_id

comment_id = 13

for pid in product_ids:
    num_comments = random.randint(3, 8)
    sampled_users = random.sample(user_ids, num_comments)
    for uid in sampled_users:
        rate = random.choice([4, 5])
        content = random.choice(comment_templates)
        comments.append((comment_id, uid, pid, rate, content, created_time))
        comment_id += 1

# Tạo file SQL để insert
insert_statements = ["INSERT INTO `comments` (id, user_id, product_id, rate, comment_content, created) VALUES"]
values = [
    f"({c[0]}, {c[1]}, {c[2]}, {c[3]}, '{c[4]}', {c[5]})"
    for c in comments
]
sql_content = insert_statements[0] + "\n" + ",\n".join(values) + ";"

# Lưu ra file
file_path = "insert_comments.sql"
with open(file_path, "w", encoding="utf-8") as f:
    f.write(sql_content)

file_path
