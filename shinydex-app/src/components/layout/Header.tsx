import { AppBar, Toolbar, Typography, Stack, Button } from '@mui/material';
import { NavLink } from 'react-router-dom';
import shinyDexLogo from '@/assets/imgs/shinydex-logo.png';

export default function Header() {
  return (
    <AppBar
      position="fixed"
      color="transparent"
      elevation={0}
      sx={{
        zIndex: '1000',
        top: 0,
        left: 0,
        right: 0,
      }}
    >
      <Toolbar sx={{ justifyContent: 'space-between' }}>
        <Stack display="flex" flexDirection="row" alignItems="center">
          <img src={shinyDexLogo} height="50" />
          <Typography variant="h6" fontWeight={700}>
            Shiny<span style={{ color: '#F5C842' }}>Dex</span>
          </Typography>
        </Stack>

        <Stack direction="row" spacing={2}>
          <Button component={NavLink} to="/dex">
            ShinyDex
          </Button>
          <Button component={NavLink} to="/hunts">
            Mes Hunts
          </Button>
          <Button component={NavLink} to="/history">
            Historique
          </Button>
          <Button component={NavLink} to="/profile">
            Profil
          </Button>
        </Stack>
      </Toolbar>
    </AppBar>
  );
}
