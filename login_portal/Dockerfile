FROM node:18-bullseye-slim

WORKDIR /app

# Install OS build deps needed by some native modules (sqlite3)
RUN apt-get update && \
	apt-get install -y --no-install-recommends python3 build-essential g++ make ca-certificates && \
	rm -rf /var/lib/apt/lists/*

# Install dependencies
COPY package.json ./
RUN npm install --production

# Copy app
COPY . .

EXPOSE 3000
ENV PORT=3000
CMD ["npm","start"]
