import { pokemonTypeColors, PokemonType } from '@/theme/pokemonTypes';

export function getTypeColor(type: PokemonType): string {
  return pokemonTypeColors[type];
}

export function getTypeGradient(types: PokemonType[]): string {
  if (types.length === 1) {
    return pokemonTypeColors[types[0]];
  }

  if (types.length === 2) {
    return `linear-gradient(135deg, 
      ${pokemonTypeColors[types[0]]}, 
      ${pokemonTypeColors[types[1]]}
    )`;
  }

  return '#888';
}
