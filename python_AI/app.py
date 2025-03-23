from flask import Flask, request, jsonify
import sys
import traceback
import numpy as np
import cv2
import tensorflow as tf
from keras.applications import MobileNetV2
from sklearn.metrics.pairwise import cosine_similarity
from livereload import Server
import pickle
import os

app = Flask(__name__)

# Ghi log vào file
log_file = "./app/logs/image_search.log"
model = MobileNetV2(weights="imagenet", include_top=False, pooling="avg")
with open("./app/data/features.pkl", "rb") as f:
    image_vectors, image_paths = pickle.load(f)

def write_log(message):
    with open(log_file, "a", encoding="utf-8") as f:
        f.write(message + "\n")

@app.route('/api/image_search', methods=['POST'])
def image_search():
    try:
        write_log("=== Bắt đầu tìm kiếm ảnh ===")
        image_file = request.files['image']
        upload_dir = "/app/upload"
        os.makedirs(upload_dir, exist_ok=True)  # Tạo thư mục nếu chưa có

        image_path = os.path.join(upload_dir, image_file.filename)
        image_file.save(image_path)
        write_log(f"Ảnh tải lên: {image_path}")

        image = cv2.imread(image_path)
        if image is None:
            raise ValueError(f"Không thể đọc ảnh: {image_path}")

        image = cv2.resize(image, (224, 224))
        image = np.expand_dims(image, axis=0)
        image = tf.keras.applications.mobilenet_v2.preprocess_input(image)
        write_log("Xử lý ảnh thành công.")

        query_vector = model.predict(image)
        write_log("Trích xuất đặc trưng thành công.")

        similarities = cosine_similarity(query_vector, image_vectors)
        
        # Số lượng ảnh tương đồng cao nhất
        i = 5
        top_matches = np.argsort(similarities[0])[-i:][::-1]
        best_match_names = [os.path.basename(image_paths[i]) for i in top_matches]

        write_log(f"Tên ảnh kết quả: {best_match_names}")

        # Xóa ảnh sau khi xử lý xong
        if os.path.exists(image_path):
            os.remove(image_path)
            write_log(f"Đã xóa ảnh: {image_path}")

        return jsonify({'success': True, 'image_names': best_match_names})

    except Exception as e:
        error_message = traceback.format_exc()
        write_log(f"❌ Lỗi xảy ra: {error_message}")
        return jsonify({'success': False, 'message': 'Lỗi xảy ra trong quá trình xử lý ảnh', 'error': str(e)})

@app.route('/')
def home():
    weather_data = {
        "city": "Ho Chi Minh",
        "temperature": 30,
        "humidity": 70,
        "description": "Partly Cloudy"
    }
    return jsonify(weather_data)

if __name__ == '__main__':
    server = Server(app.wsgi_app)
    server.serve(host='0.0.0.0', port=5000, debug=True)