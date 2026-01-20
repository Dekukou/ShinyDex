import { IconButton, InputAdornment, TextField } from '@mui/material';
import { Visibility, VisibilityOff } from '@mui/icons-material';
import { useState } from 'react';

interface Props {
  value: string;
  onChange: (v: string) => void;
}

export default function PasswordField({ value, onChange }: Props) {
  const [visible, setVisible] = useState(false);

  return (
    <TextField
      fullWidth
      type={visible ? 'text' : 'password'}
      label="Mot de passe"
      value={value}
      onChange={(e) => onChange(e.target.value)}
      InputProps={{
        startAdornment: <InputAdornment position="start">🔒</InputAdornment>,
        endAdornment: (
          <InputAdornment position="end">
            <IconButton onClick={() => setVisible((v) => !v)}>
              {visible ? <VisibilityOff /> : <Visibility />}
            </IconButton>
          </InputAdornment>
        ),
      }}
    />
  );
}
