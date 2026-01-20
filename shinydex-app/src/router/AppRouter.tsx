import { Routes, Route, Navigate } from 'react-router-dom';

import ShinyDexPage from '@/pages/ShinyDex/ShinyDexPage';
import PokemonDetailPage from '@/pages/Pokemon/PokemonDetailPage';
import HuntsPage from '@/pages/Hunts/HuntsPage';
import ProfilePage from '@/pages/Profile/ProfilePage';
import LoginPage from '@/pages/Auth/LoginPage';
import ProtectedRoute from '@/components/auth/ProtectedRoute';

export default function AppRouter() {
  return (
    <Routes>
      <Route path="/" element={<Navigate to="/dex" />} />

      <Route path="/login" element={<LoginPage />} />

      <Route
        path="/dex"
        element={
          <ProtectedRoute>
            <ShinyDexPage />
          </ProtectedRoute>
        }
      />

      <Route
        path="/dex/:pokemonId"
        element={
          <ProtectedRoute>
            <PokemonDetailPage />
          </ProtectedRoute>
        }
      />

      <Route
        path="/hunts"
        element={
          <ProtectedRoute>
            <HuntsPage />
          </ProtectedRoute>
        }
      />

      <Route
        path="/profile"
        element={
          <ProtectedRoute>
            <ProfilePage />
          </ProtectedRoute>
        }
      />
    </Routes>
  );
}
