import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '@/api/axios';

type LoginResponse = {
  token: string;
};

export function useLogin() {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const navigate = useNavigate();

  const submit = async (username: string, password: string) => {
    setLoading(true);
    setError(null);

    try {
      const response = await api.post<LoginResponse>('/login_check', {
        username,
        password,
      });

      localStorage.setItem('token', response.data.token);

      navigate('/dex');
    } catch (e: any) {
      setError('Identifiants invalides');
      throw e;
    } finally {
      setLoading(false);
    }
  };

  return {
    submit,
    loading,
    error,
  };
}
