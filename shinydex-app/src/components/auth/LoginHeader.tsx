import { Box, Typography } from '@mui/material';
import shinyDexLogo from '@/assets/imgs/shinydex-logo.png';

export default function LoginHeader() {
  return (
    <Box textAlign="center" mb={3}>
      <Box
        component="img"
        src={shinyDexLogo}
        alt="ShinyDex"
        height={192}
        mb={1}
      />
      <Typography variant="h1">ShinyDex</Typography>
    </Box>
  );
}
