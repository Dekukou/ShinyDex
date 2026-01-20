import api from './axios';

type LoginPayload = {
  username: string;
  password: string;
};

type LoginResponse = {
  token: string;
};

export async function login(payload: LoginPayload): Promise<void> {
  const response = await api.post<LoginResponse>('/login_check', payload);

  localStorage.setItem('token', response.data.token);
}

export function logout(): void {
  localStorage.removeItem('token');
}
