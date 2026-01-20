import type { ReactNode } from 'react';
import useAuth from '@/hooks/useAuth';

type Props = {
  children: ReactNode;
};

export default function ProtectedRoute({ children }: Props) {
  const { loading, isAuthenticated } = useAuth();

  if (loading) {
    return null;
  }

  if (!isAuthenticated) {
    return null;
  }

  return <>{children}</>;
}
