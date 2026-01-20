import { Card, CardContent, Typography } from '@mui/material';
import LoginForm from './LoginForm';

export default function LoginCard() {
  return (
    <Card sx={{ width: 420 }}>
      <CardContent>
        <Typography variant="h2" textAlign="center" mb={1}>
          Connexion
        </Typography>
        <Typography variant="body2" textAlign="center" mb={3}>
          Connecte-toi à ton compte <strong>ShinyDex</strong>
        </Typography>
        <LoginForm />
      </CardContent>
    </Card>
  );
}
