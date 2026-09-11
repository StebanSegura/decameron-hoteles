import { useCallback, useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import Button from 'react-bootstrap/Button';
import { hotelesApi } from '../api/hoteles';
import { configuracionesApi } from '../api/configuraciones';
import { catalogosApi } from '../api/catalogos';
import ConfiguracionesTable from '../components/ConfiguracionesTable';
import ConfiguracionFormModal from '../components/ConfiguracionFormModal';
import AlertMessage from '../components/AlertMessage';

export default function HotelDetallePage() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [hotel, setHotel] = useState(null);
  const [configuraciones, setConfiguraciones] = useState([]);
  const [tiposHabitacion, setTiposHabitacion] = useState([]);
  const [alerta, setAlerta] = useState(null);

  const [showModal, setShowModal] = useState(false);
  const [configEditando, setConfigEditando] = useState(null);
  const [errorForm, setErrorForm] = useState(null);

  const cargar = useCallback(async () => {
    const [h, configs] = await Promise.all([
      hotelesApi.obtener(id),
      configuracionesApi.listarPorHotel(id),
    ]);
    setHotel(h);
    setConfiguraciones(configs);
  }, [id]);

  useEffect(() => {
    cargar();
    catalogosApi.tiposHabitacion().then(setTiposHabitacion);
  }, [cargar]);

  function abrirNuevo() {
    setConfigEditando(null);
    setErrorForm(null);
    setShowModal(true);
  }

  function abrirEditar(config) {
    setConfigEditando(config);
    setErrorForm(null);
    setShowModal(true);
  }

  async function guardarConfig(data) {
    try {
      if (configEditando) {
        await configuracionesApi.actualizar(id, configEditando.id, data);
      } else {
        await configuracionesApi.crear(id, data);
      }
      setShowModal(false);
      await cargar();
    } catch (e) {
      setErrorForm({ message: e.message, errors: e.errors });
    }
  }

  async function eliminarConfig(configId) {
    if (!confirm('¿Eliminar esta configuración de habitación?')) return;
    try {
      await configuracionesApi.eliminar(id, configId);
      await cargar();
    } catch (e) {
      setAlerta({ message: e.message });
    }
  }

  if (!hotel) return <p className="text-center text-muted py-4">Cargando...</p>;

  const totalConfigurado = configuraciones.reduce((acc, c) => acc + c.cantidad, 0);
  const disponibles = hotel.numero_habitaciones - totalConfigurado;
  const claseValor = disponibles <= 0 ? 'full' : disponibles <= hotel.numero_habitaciones * 0.1 ? 'warn' : '';

  return (
    <>
      <div className="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h2 className="h4 mb-0">{hotel.nombre}</h2>
        <Button variant="outline-secondary" size="sm" onClick={() => navigate('/')}>← Volver al listado</Button>
      </div>

      <div className="row g-3 mb-4">
        {[
          ['Ciudad', hotel.ciudad_nombre],
          ['NIT', hotel.nit],
          ['Dirección', hotel.direccion],
          ['Máximo', hotel.numero_habitaciones],
          ['Configuradas', totalConfigurado],
          ['Disponibles', disponibles, claseValor],
        ].map(([label, value, extra]) => (
          <div className="col-6 col-md-4 col-lg-2" key={label}>
            <div className="stat-card">
              <span className="stat-label">{label}</span>
              <span className={`stat-value ${extra || ''}`}>{value}</span>
            </div>
          </div>
        ))}
      </div>

      <AlertMessage message={alerta?.message} />

      <div className="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h3 className="h5 mb-0">Configuraciones de habitación</h3>
        <Button variant="primary" size="sm" onClick={abrirNuevo}>+ Agregar configuración</Button>
      </div>

      <ConfiguracionesTable configuraciones={configuraciones} onEditar={abrirEditar} onEliminar={eliminarConfig} />

      <ConfiguracionFormModal
        show={showModal}
        onHide={() => setShowModal(false)}
        onSubmit={guardarConfig}
        tiposHabitacion={tiposHabitacion}
        configInicial={configEditando}
        error={errorForm}
      />
    </>
  );
}
