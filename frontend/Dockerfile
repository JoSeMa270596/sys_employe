FROM node:20-alpine

WORKDIR /app

# Instala Angular CLI globalmente
RUN npm install -g @angular/cli@17

# Copia archivos de dependencias primero
COPY package.json ./

# Instala dependencias
RUN npm install --legacy-peer-deps

# Copia todo el código fuente
COPY . .

# Configura Angular CLI para no preguntar por analytics
RUN ng config --global cli.analytics false

# Usa este comando para desarrollo
CMD ["ng", "serve", "--host", "0.0.0.0", "--port", "4200", "--poll", "2000"]
