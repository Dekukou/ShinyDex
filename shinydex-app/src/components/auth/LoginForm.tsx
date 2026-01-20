import { Box, Button, Link, TextField } from '@mui/material';
import { useState } from 'react';
import PasswordField from '@/components/ui/PasswordField';
import { useLogin } from '@/hooks/useLogin';

export default function LoginForm() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const { submit, loading } = useLogin();

  return (
    <Box component="form" display="grid" gap={2}>
      <TextField
        fullWidth
        label="Nom d'utilisateur"
        value={username}
        onChange={(e) => setUsername(e.target.value)}
        InputProps={{ startAdornment: '👤' }}
      />

      <PasswordField value={password} onChange={setPassword} />

      <Box textAlign="right">
        <Link href="#" variant="body2">
          Mot de passe oublié ?
        </Link>
      </Box>

      <Button
        variant="contained"
        size="large"
        disabled={loading}
        onClick={() => submit(username, password)}
      >
        Se connecter
      </Button>

      <Box textAlign="center">
        <Link href="#" variant="body2">
          Pas encore de compte ? <strong>S'inscrire</strong>
        </Link>
      </Box>
    </Box>
  );
}
