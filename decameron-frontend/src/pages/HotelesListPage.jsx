import { useCallback, useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import Button from 'react-bootstrap/Button';
import { hotelesApi } from '../api/hoteles';
import { configuracionesApi } from '../api/configuraciones';
import { catalogosApi } from '../api/catalogos';
import HotelesTable from '../components/HotelesTable';
import HotelFormModal from '../components/HotelFormModal';
import AlertMessage from '../components/AlertMessage';

export default function HotelesListPage() {
  const navigate = useNavigate();

  const [hoteles, setHoteles] = useState([]);
  const [configuradasPorHotel, setConfiguradasPorHotel] = useState({});
  const [ciudades, setCiudades] = useState([]);
  const [cargando, setCargando] = useState(true);
  const [alerta, setAlerta] = useState(null);

  const [showModal, setShowModal] = useState(false);
  const [hotelEditando, setHotelEditando] = useState(null);
  const [errorForm, setErrorForm] = useState(null);

  const cargarHoteles = useCallback(async () => {
    setCargando(true);
    try {
      const lista = await hotelesApi.listar();
      setHoteles(lista);

      const entradas = await Promise.all(
        lista.map(async (h) => {
          try {
            const configs = await configuracionesApi.listarPorHotel(h.id);
            return [h.id, configs.reduce((acc, c) => acc + c.cantidad, 0)];
          } catch {
            return [h.id, 0];
          }
        })
      );
      setConfiguradasPorHotel(Object.fromEntries(entradas));
    } catch (e) {
      setAlerta({ message: e.message });
    } finally {
      setCargando(false);
    }
  }, []);

  useEffect(() => {
    cargarHoteles();
    catalogosApi.ciudades().then(setCiudades);
  }, [cargarHoteles]);

  function abrirNuevo() {
    setHotelEditando(null);
    setErrorForm(null);
    setShowModal(true);
  }

  async function abrirEditar(id) {
    const hotel = await hotelesApi.obtener(id);
    setHotelEditando(hotel);
    setErrorForm(null);
    setShowModal(true);
  }

  async function guardarHotel(data) {
    try {
      if (hotelEditando) {
        await hotelesApi.actualizar(hotelEditando.id, data);
      } else {
        await hotelesApi.crear(data);
      }
      setShowModal(false);
      await cargarHoteles();
    } catch (e) {
      setErrorForm({ message: e.message, errors: e.errors });
    }
  }

  async function eliminarHotel(id) {
    if (!confirm('¿Eliminar este hotel y todas sus configuraciones de habitación?')) return;
    try {
      await hotelesApi.eliminar(id);
      await cargarHoteles();
    } catch (e) {
      setAlerta({ message: e.message });
    }
  }

  return (
    <>
      <div className="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h2 className="h4 mb-0">Hoteles registrados</h2>
        <Button variant="primary" onClick={abrirNuevo}>+ Nuevo hotel</Button>
      </div>

      <AlertMessage message={alerta?.message} />

      {cargando ? (
        <p className="text-center text-muted py-4">Cargando...</p>
      ) : (
        <HotelesTable
          hoteles={hoteles}
          configuradasPorHotel={configuradasPorHotel}
          onVer={(id) => navigate(`/hoteles/${id}`)}
          onEditar={abrirEditar}
          onEliminar={eliminarHotel}
        />
      )}

      <HotelFormModal
        show={showModal}
        onHide={() => setShowModal(false)}
        onSubmit={guardarHotel}
        ciudades={ciudades}
        hotelInicial={hotelEditando}
        error={errorForm}
      />
    </>
  );
}
