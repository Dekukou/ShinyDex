import { Container, Typography } from '@mui/material';
import PcBoxGrid from '@/components/shinydex/PcBoxGrid';

export default function ShinyDexPage() {
  return (
    <Container maxWidth="xl">
      <Typography variant="h4" fontWeight="bold" mb={3}>
        ShinyDex
      </Typography>

      <PcBoxGrid />
    </Container>
  );
}
