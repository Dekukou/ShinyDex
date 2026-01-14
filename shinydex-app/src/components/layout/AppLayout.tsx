import { Box } from '@mui/material';
import Header from './Header';
import PageContainer from './PageContainer';
// import Footer from './Footer';

type Props = {
  children: React.ReactNode;
};

export default function AppLayout({ children }: Props) {
  return (
    <Box
      display="flex"
      flexDirection="column"
      minHeight="100vh"
      position="relative"
      pt="112px"
      pb="50px"
    >
      <Header />

      <PageContainer>{children}</PageContainer>

      {/* <Footer /> */}
    </Box>
  );
}
