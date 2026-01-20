import axios from 'axios';

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

/**
Intercepteur pour injecter le JWT automatiquement
*/
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});

/**
Intercepteur de réponse (optionnel mais conseillé)
*/
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    // Exemple : logout auto si token invalide
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
    }

    return Promise.reject(error);
  }
);

export default apiClient;
