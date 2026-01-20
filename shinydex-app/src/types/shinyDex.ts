export interface ShinyDexBoxDto {
  id: number;
  name: string;
  shinyCount: number;
  totalCount: number;
}

export interface ShinyDexReadDto {
  boxes: ShinyDexBoxDto[];
  totalBasePokemon: number;
  totalBaseShiny: number | null;
  totalGlobalShiny: number | null;
  totalBoxes: number;
  hasMore: boolean;
}
