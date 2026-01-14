import { ThemeProvider, CssBaseline } from '@mui/material';
import { BrowserRouter } from 'react-router-dom';

import theme from './theme/theme';
import AppLayout from './components/layout/AppLayout';
import AppRouter from './router/AppRouter';

export default function App() {
  return (
    <ThemeProvider theme={theme}>
      <CssBaseline />
      <BrowserRouter>
        <AppLayout>
          <AppRouter />
        </AppLayout>
      </BrowserRouter>
    </ThemeProvider>
  );
}
