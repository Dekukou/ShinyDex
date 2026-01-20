import { Box } from '@mui/material';
import PcBox from './PcBox';
import { getShinyDex } from '@/api/shinyDex.api';
import { useNavigate } from 'react-router-dom';
import { useEffect, useState } from 'react';

const BOX_SIZE = 30;
const BOXES_PER_PAGE = 4;

export default function ShinyDexPage() {
  const navigate = useNavigate();

  const [boxes, setBoxes] = useState<any[]>([]);
  const [offset, setOffset] = useState(0);
  const [hasMore, setHasMore] = useState(false);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    loadBoxes(0, true);
  }, []);

  const loadBoxes = async (offsetValue: number, reset = false) => {
    setLoading(true);

    try {
      const res = await getShinyDex();
      console.log(res.boxes);
      //   {
      //   offset: offsetValue,
      //   boxes: BOXES_PER_PAGE,
      //   limit: BOX_SIZE,
      // }

      setBoxes(reset ? res.boxes : [...boxes, ...res.boxes]);
      setHasMore(res.hasMore);
      setOffset(offsetValue);
    } finally {
      setLoading(false);
    }
  };

  return (
    <Box
      sx={{
        display: 'grid',
        gridTemplateColumns: {
          xs: '1fr',
          md: '1fr 1fr',
        },
        gap: 6,
        width: '100%',
      }}
    >
      {boxes.map((pokemons, index) => (
        <Box key={index} sx={{ width: '100%' }}>
          <PcBox title={`Boîte ${index + 1}`} pokemons={pokemons.entries} />
        </Box>
      ))}
    </Box>
  );
}
