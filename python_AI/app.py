from flask import Flask, jsonify
from livereload import Server

app = Flask(__name__)

@app.route('/')
def home():
    weather_data = {
        "city": "HN",
        "temperature": 30,
        "humidity": 70,
        "description": "Partly Cloudy"
    }
    return jsonify(weather_data)

if __name__ == '__main__':
    server = Server(app.wsgi_app)
    server.serve(host='0.0.0.0', port=5000, debug=True)
