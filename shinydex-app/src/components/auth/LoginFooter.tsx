import { Box, Link, Typography } from '@mui/material';

export default function LoginFooter() {
  return (
    <Box mt={3} textAlign="center">
      <Typography variant="caption">
        <Link href="#" underline="hover">
          Mentions légales
        </Link>
        {' · '}
        <Link href="#" underline="hover">
          Vie privée
        </Link>
      </Typography>
    </Box>
  );
}
