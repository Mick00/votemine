sudo docker build -f dockers/nginx.dockerfile -t hovoh/votemine-server:latest .
sudo docker build -f dockers/app.dockerfile -t hovoh/votemine-app:latest .
sudo docker build -f dockers/worker.dockerfile -t hovoh/votemine-worker:latest .
