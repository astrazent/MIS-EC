import pymysql
import random
import time
from decimal import Decimal

# Kết nối MySQL
connection = pymysql.connect(
    host='sql3.freesqldatabase.com',
    user='sql3769289',
    password='laUmWFHXPe',  
    database='sql3769289',
    charset='utf8mb4',
    cursorclass=pymysql.cursors.DictCursor
)

messages = [
    "Giao nhanh giúp mình nhé!",
    "Giao hàng trong giờ hành chính.",
    "Liên hệ trước khi giao.",
    "Hàng dễ vỡ, xin nhẹ tay.",
    "Ưu tiên giao sáng.",
    "Giao tại văn phòng.",
    "Không giao cuối tuần."
]

try:
    with connection.cursor() as cursor:
        # Bước 1: Lấy danh sách user
        cursor.execute("SELECT id, name, email, phone, address FROM user")
        users = cursor.fetchall()
        if not users:
            raise Exception("Bảng user hiện tại không có dữ liệu!")

        # Bước 2: Lấy danh sách coupon
        cursor.execute("SELECT id, measure, value FROM coupon WHERE status = 1")
        coupons = cursor.fetchall()

        # Bước 3: Fake bảng transaction
        transaction_ids = []
        transaction_coupon_mapping = {}  # transaction_id => coupon_id

        for _ in range(500):  # Fake 500 giao dịch
            user = random.choice(users)

            status = random.choice([0, 1, 2, 3])
            use_coupon = random.random() < 0.1  # 10% xác suất

            coupon_id = None
            if use_coupon and coupons:
                coupon = random.choice(coupons)
                coupon_id = coupon['id']

            discount_amount = 0.00
            payment = random.choice(["vnpay", "vietqr", "cash"])
            created = int(time.time()) - random.randint(0, 1000000)

            sql_transaction = """
                INSERT INTO `transaction` 
                (status, user_id, user_name, user_email, user_phone, user_address, message, coupon_id, discount_amount, amount, payment, created)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            """

            message = random.choice(messages)
            amount = 0.00  # sẽ cập nhật sau

            cursor.execute(sql_transaction, (
                status, user['id'], user['name'], user['email'], user['phone'], user['address'],
                message, coupon_id, discount_amount, amount, payment, created
            ))

            transaction_id = cursor.lastrowid
            transaction_ids.append(transaction_id)

            if coupon_id:
                transaction_coupon_mapping[transaction_id] = coupon

        # Bước 4: Lấy danh sách sản phẩm
        cursor.execute("SELECT id, price FROM product")
        products = cursor.fetchall()
        if not products:
            raise Exception("Bảng product hiện tại không có dữ liệu!")

        # Bước 5: Fake bảng order
        for _ in range(1000):
            transaction_id = random.choice(transaction_ids)
            product = random.choice(products)

            qty = random.randint(1, 5)
            amount = product['price'] * qty
            status = random.choice([0, 1, 2])

            sql_order = """
                INSERT INTO `order` (transaction_id, product_id, qty, amount, status)
                VALUES (%s, %s, %s, %s, %s)
            """
            cursor.execute(sql_order, (transaction_id, product['id'], qty, amount, status))

        # Bước 6: Cập nhật amount và discount cho transaction
        cursor.execute("""
            SELECT transaction_id, SUM(amount) as total_amount
            FROM `order`
            GROUP BY transaction_id
        """)
        transaction_amounts = cursor.fetchall()

        for item in transaction_amounts:
            transaction_id = item['transaction_id']
            total_amount = item['total_amount']

            discount_amount = 0.00

            if transaction_id in transaction_coupon_mapping:
                coupon = transaction_coupon_mapping[transaction_id]
                measure = coupon['measure']
                value = coupon['value']

                if measure == 1:
                    # Giảm trực tiếp số tiền
                    discount_amount = min(Decimal(value), total_amount)
                elif measure == 2:
                    # Giảm theo phần trăm
                    discount_amount = total_amount * (Decimal(value) / Decimal(100))

                total_amount = max(total_amount - discount_amount, Decimal(0))
                

            sql_update_transaction = """
                UPDATE `transaction`
                SET discount_amount = %s,
                    amount = %s
                WHERE id = %s
            """
            cursor.execute(sql_update_transaction, (round(discount_amount, 2), round(total_amount, 2), transaction_id))

    connection.commit()
    print("Fake dữ liệu bảng transaction và order thành công (có coupon giảm giá)!")
finally:
    connection.close()
