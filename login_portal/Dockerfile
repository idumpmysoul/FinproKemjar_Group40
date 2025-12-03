FROM node:18-alpine
WORKDIR /app

# Install dependencies
COPY package.json ./
RUN npm install --production

# Copy app
COPY . .

EXPOSE 3000
ENV PORT=3000
CMD ["npm","start"]
