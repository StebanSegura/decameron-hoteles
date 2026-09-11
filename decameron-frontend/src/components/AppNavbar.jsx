import Navbar from 'react-bootstrap/Navbar';
import Container from 'react-bootstrap/Container';

export default function AppNavbar() {
  return (
    <Navbar className="navbar-decameron" variant="dark">
      <Container>
        <Navbar.Brand className="d-flex align-items-center gap-2">
          <span className="brand-mark">D</span>
          <span>
            <span className="d-block fw-bold lh-sm">Hoteles Decameron de Colombia</span>
            <small className="text-white-50">Gestión de hoteles y habitaciones</small>
          </span>
        </Navbar.Brand>
      </Container>
    </Navbar>
  );
}
