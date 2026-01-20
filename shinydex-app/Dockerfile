# Node Debian stable pour compatibilité ESM
FROM node:20-bullseye

WORKDIR /app

# Copier package.json + package-lock.json
COPY package.json package-lock.json* ./

# Installer toutes les dépendances
RUN npm ci

# Copier tout le projet
COPY . .

EXPOSE 5173

# Lancer vite via npx pour forcer l'exécution du binaire local
CMD ["npx", "vite", "--host"]
