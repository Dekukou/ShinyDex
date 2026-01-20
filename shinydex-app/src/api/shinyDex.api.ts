import api from './axios';
import type { ShinyDexReadDto } from '@/types/shinyDex';

export const getShinyDex = async (): Promise<ShinyDexReadDto> => {
  const response = await api.get<ShinyDexReadDto>('/shiny-dex');
  return response.data;
};
