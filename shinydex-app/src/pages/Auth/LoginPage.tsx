// import GradientBackground from '../../../components/ui/GradientBackground';
import LoginHeader from '../../components/auth/LoginHeader';
import LoginCard from '../../components/auth/LoginCard';
import LoginFooter from '../../components/auth/LoginFooter';

export default function LoginPage() {
  return (
    // <GradientBackground>
    <div>
      <LoginHeader />
      <LoginCard />
      <LoginFooter />
    </div>
    // </GradientBackground>
  );
}

// import { useState } from 'react';
// import { login as loginApi } from '@/api/auth.api';
// import { useAuth } from '@/auth/AuthContext';
// import { AuthCard } from '@/components/layout/AuthCard';
// import { FormInput } from '@/components/form/FormInput';
// import { FormButton } from '@/components/form/FormButton';
// import { FormError } from '@/components/form/FormError';

// export function LoginPage() {
//   const { login } = useAuth();

//   const [username, setUsername] = useState('');
//   const [password, setPassword] = useState('');
//   const [error, setError] = useState('');
//   const [loading, setLoading] = useState(false);

//   const handleSubmit = async (e: React.FormEvent) => {
//     e.preventDefault();
//     setError('');
//     setLoading(true);

//     try {
//       const response = await loginApi({ username, password });
//       login(response.token);
//     } catch (e) {
//       setError('Mauvais identifiants');
//     } finally {
//       setLoading(false);
//     }
//   };

//   return (
//     <AuthCard>
//       <form onSubmit={handleSubmit}>
//         <FormInput
//           label="Nom d'utilisateur"
//           value={username}
//           onChange={setUsername}
//         />
//         <FormInput
//           label="Mot de passe"
//           type="password"
//           value={password}
//           onChange={setPassword}
//         />

//         {error && <FormError message={error} />}

//         <FormButton disabled={loading}>
//           {loading ? 'Connexion...' : 'Se connecter'}
//         </FormButton>
//       </form>
//     </AuthCard>
//   );
// }
