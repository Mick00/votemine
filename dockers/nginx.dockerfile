FROM hovoh/npm-nginx:latest

WORKDIR /tmp
COPY . /tmp
RUN npm i
RUN npm run production
RUN mkdir -p ./public/storage
RUN cp -r ./storage/app/public/* ./public/storage
RUN mkdir -p /var/www/public
RUN cp -r ./public /var/www
RUN rm -rf /tmp

