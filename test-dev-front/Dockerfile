# Copy code
FROM nginx:alpine

# Copy folders
RUN mkdir /app
COPY . /app

COPY nginx.conf /etc/nginx/nginx.conf

# Install NodeJS and NPM
RUN apk update
RUN apk add --update nodejs nodejs-npm

# Install Angular CLI
RUN npm install -g @angular/cli

# Build Angular project
WORKDIR /app
RUN npm install
RUN ng build --output-path /usr/share/nginx/html

