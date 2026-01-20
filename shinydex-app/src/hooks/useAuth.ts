import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '@/api/axios';

export default function useAuth() {
  const [loading, setLoading] = useState(true);
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    api
      .get('/me')
      .then(() => {
        setIsAuthenticated(true);
      })
      .catch((error) => {
        if (error.response?.status === 401) {
          setIsAuthenticated(false);
          navigate('/login', { replace: true });
        }
      })
      .finally(() => {
        setLoading(false);
      });
  }, [navigate]);

  return { loading, isAuthenticated };
}
