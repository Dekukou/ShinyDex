import { Box, Typography } from '@mui/material';
import PokemonTile from './PokemonTile';

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
  title: string;
  pokemons: Pokemon[];
};

const TOTAL_SLOTS = 30;

export default function PcBox({ title, pokemons }: Props) {
  const slots = Array.from({ length: TOTAL_SLOTS }).map((_, index) => {
    return pokemons[index] ?? null;
  });

  return (
    <Box
      sx={{
        border: '2px solid',
        borderColor: 'primary.main',
        borderRadius: 2,
        p: 3,
        backgroundColor: 'background.paper',
      }}
    >
      <Typography variant="subtitle1" fontWeight="bold" mb={2}>
        {title}
      </Typography>

      <Box
        sx={{
          display: 'grid',
          gridTemplateColumns: 'repeat(5, 1fr)',
          gridTemplateRows: 'repeat(6, 1fr)',
          gap: 1,
        }}
      >
        {slots.map((pokemon, index) =>
          pokemon ? (
            <PokemonTile key={pokemon.id} pokemon={pokemon} />
          ) : (
            <EmptySlot key={`empty-${index}`} />
          ),
        )}
      </Box>
    </Box>
  );
}

function EmptySlot() {
  return (
    <Box
      sx={{
        aspectRatio: '1 / 1',
        borderRadius: 1,
        backgroundColor: 'action.disabledBackground',
      }}
    />
  );
}
