import { useEffect } from 'react'
import api from './api/axios'

function App() {
  useEffect(() => {
    api.get('/health')
      .then(res => {
        console.log('STATUS:', res.status)
        console.log('DATA:', res.data)
      })
      .catch(err => {
        console.error('AXIOS ERROR ❌', err)
      })
  }, [])

  return <h1>ShinyDex</h1>
}

export default App
