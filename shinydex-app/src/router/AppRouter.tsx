import { Routes, Route, Navigate } from 'react-router-dom';

import ShinyDexPage from '@/pages/ShinyDex/ShinyDexPage';
import PokemonDetailPage from '@/pages/Pokemon/PokemonDetailPage';
import HuntsPage from '@/pages/Hunts/HuntsPage';
import ProfilePage from '@/pages/Profile/ProfilePage';
import LoginPage from '@/pages/Auth/LoginPage';

export default function AppRouter() {
  return (
    <Routes>
      <Route path="/" element={<Navigate to="/dex" />} />

      <Route path="/dex" element={<ShinyDexPage />} />
      <Route path="/dex/:pokemonId" element={<PokemonDetailPage />} />

      <Route path="/hunts" element={<HuntsPage />} />
      <Route path="/profile" element={<ProfilePage />} />

      <Route path="/login" element={<LoginPage />} />
    </Routes>
  );
}
