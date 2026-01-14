import { createTheme } from '@mui/material/styles';

const theme = createTheme({
  palette: {
    mode: 'dark',

    primary: {
      main: '#F5C842', // Jaune shiny
      contrastText: '#0B0F14',
    },

    secondary: {
      main: '#4EA1D3', // Bleu UI
    },

    background: {
      default: '#0A0E13', // Fond global
      paper: '#121822', // Surfaces / cards
    },

    text: {
      primary: '#E6EAF0',
      secondary: '#9AA4B2',
      disabled: '#6B7280',
    },

    divider: 'rgba(255,255,255,0.12)',
  },

  typography: {
    fontFamily: ['Inter', 'Roboto', 'Helvetica', 'Arial', 'sans-serif'].join(
      ','
    ),

    h1: {
      fontSize: '2.1rem',
      fontWeight: 800,
      letterSpacing: '-0.02em',
    },
    h2: {
      fontSize: '1.6rem',
      fontWeight: 700,
    },
    h3: {
      fontSize: '1.25rem',
      fontWeight: 600,
    },
    body1: {
      fontSize: '0.95rem',
    },
    body2: {
      fontSize: '0.85rem',
      color: '#9AA4B2',
    },
    button: {
      textTransform: 'none',
      fontWeight: 600,
    },
    caption: {
      color: '#9AA4B2',
    },
  },

  shape: {
    borderRadius: 12,
  },

  components: {
    MuiCssBaseline: {
      styleOverrides: {
        body: {
          backgroundColor: '#0A0E13',
          backgroundImage: `
            radial-gradient(circle at top, rgba(245,200,66,0.08), transparent 40%),
            radial-gradient(circle at bottom, rgba(78,161,211,0.08), transparent 40%)
          `,
        },
      },
    },

    MuiPaper: {
      styleOverrides: {
        root: {
          backgroundColor: 'rgba(18,24,34,0.85)',
          backdropFilter: 'blur(6px)',
          border: '1px solid rgba(255,255,255,0.08)',
          boxShadow: `
            0 8px 20px rgba(0,0,0,0.45),
            inset 0 1px 0 rgba(255,255,255,0.04)
          `,
        },
      },
    },

    MuiCard: {
      styleOverrides: {
        root: {
          backgroundColor: 'rgba(18,24,34,0.85)',
          backdropFilter: 'blur(6px)',
          border: '1px solid rgba(255,255,255,0.08)',
          boxShadow: `
            0 8px 20px rgba(0,0,0,0.45),
            inset 0 1px 0 rgba(255,255,255,0.04)
          `,
        },
      },
    },

    MuiButton: {
      styleOverrides: {
        root: {
          borderRadius: 10,
          paddingInline: 16,
        },
        containedPrimary: {
          boxShadow: '0 4px 14px rgba(245,200,66,0.4)',
          '&:hover': {
            boxShadow: '0 6px 20px rgba(245,200,66,0.6)',
            transform: 'translateY(-1px)',
          },
        },
      },
    },

    MuiChip: {
      styleOverrides: {
        root: {
          fontWeight: 600,
          borderRadius: 8,
        },
      },
    },

    MuiTabs: {
      styleOverrides: {
        indicator: {
          height: 3,
          borderRadius: 3,
          backgroundColor: '#F5C842',
        },
      },
    },

    MuiTab: {
      styleOverrides: {
        root: {
          fontWeight: 600,
          textTransform: 'none',
          opacity: 0.6,
          '&.Mui-selected': {
            opacity: 1,
          },
        },
      },
    },

    MuiTextField: {
      styleOverrides: {
        root: {
          backgroundColor: 'rgba(18,24,34,0.85)',
          borderRadius: 10,
        },
      },
    },

    MuiDivider: {
      styleOverrides: {
        root: {
          borderColor: 'rgba(255,255,255,0.12)',
        },
      },
    },

    MuiTableCell: {
      styleOverrides: {
        root: {
          borderBottom: '1px solid rgba(255,255,255,0.06)',
        },
        head: {
          color: '#9AA4B2',
          fontWeight: 600,
        },
      },
    },

    MuiLinearProgress: {
      styleOverrides: {
        root: {
          height: 8,
          borderRadius: 4,
          backgroundColor: 'rgba(255,255,255,0.08)',
        },
        bar: {
          borderRadius: 4,
        },
      },
    },
  },
});

export default theme;
