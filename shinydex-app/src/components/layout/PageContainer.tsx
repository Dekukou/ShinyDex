import { Box, Typography } from '@mui/material';

type Props = {
  title?: string;
  children: React.ReactNode;
};

export default function PageContainer({ title, children }: Props) {
  return (
    <Box maxWidth="1400px" mx="auto">
      {title && (
        <Typography variant="h1" mb={3}>
          {title}
        </Typography>
      )}
      {children}
    </Box>
  );
}
