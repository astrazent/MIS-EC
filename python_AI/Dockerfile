# Sử dụng image Python chính thức
FROM python:3.9

# Thiết lập thư mục làm việc trong container
WORKDIR /app

# Copy file requirements.txt vào container
COPY requirements.txt requirements.txt

# Cài đặt các thư viện cần thiết
RUN pip install --no-cache-dir -r requirements.txt

# Chạy ứng dụng Flask
CMD ["python", "app.py"]
