import { Box, Typography } from '@mui/material';
import { useNavigate } from 'react-router-dom';

type Pokemon = {
  id: number;
  formKey: string;
  formType: string;
  isShinyCaptured: boolean;
  name: string;
  pokedexNumber: number;
  sprite: string;
};

type Props = {
  pokemon: Pokemon;
};

export default function PokemonTile({ pokemon }: Props) {
  const navigate = useNavigate();
  const sprite = pokemon.sprite.replace(/.*?(\/sprites\/.*)/, '$1');

  return (
    <Box
      onClick={() => navigate(`/dex/${pokemon.id}`)}
      sx={{
        cursor: 'pointer',
        aspectRatio: '1 / 1',
        borderRadius: 1,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
        '&:hover': {
          backgroundColor: 'action.hover',
        },
      }}
    >
      <img
        src={`${import.meta.env.VITE_API_IMAGES}${sprite}`}
        alt={pokemon.name}
        style={{ width: '64px', height: 'auto' }}
      />

      <Typography variant="caption" noWrap>
        {pokemon.name}
      </Typography>
    </Box>
  );
}
