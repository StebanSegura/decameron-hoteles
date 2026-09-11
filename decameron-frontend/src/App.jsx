import { Routes, Route } from 'react-router-dom';
import Container from 'react-bootstrap/Container';
import AppNavbar from './components/AppNavbar';
import HotelesListPage from './pages/HotelesListPage';
import HotelDetallePage from './pages/HotelDetallePage';

export default function App() {
  return (
    <>
      <AppNavbar />
      <Container className="py-4">
        <Routes>
          <Route path="/" element={<HotelesListPage />} />
          <Route path="/hoteles/:id" element={<HotelDetallePage />} />
        </Routes>
      </Container>
      <footer className="text-center text-muted small py-4 border-top">
        Prueba técnica · Backend Laravel (REST) · Frontend React desacoplado
      </footer>
    </>
  );
}
