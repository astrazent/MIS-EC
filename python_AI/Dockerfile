# Sử dụng Python image
FROM python:3.9

# Copy mã nguồn vào container
WORKDIR /app
COPY . /app

# Cài đặt các thư viện cần thiết
# RUN pip install --no-cache-dir -r requirements.txt

# Expose cổng API nếu có
EXPOSE 5000

# Khởi động ứng dụng Python
CMD ["python", "app.py"]
