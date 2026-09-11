import { useEffect, useState } from 'react';
import Modal from 'react-bootstrap/Modal';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import AlertMessage from './AlertMessage';
import { catalogosApi, capitalizar } from '../api/catalogos';

const VACIO = { tipo_habitacion_id: '', acomodacion_id: '', cantidad: '' };

export default function ConfiguracionFormModal({ show, onHide, onSubmit, tiposHabitacion, configInicial, error }) {
  const [form, setForm] = useState(VACIO);
  const [acomodaciones, setAcomodaciones] = useState([]);

  useEffect(() => {
    if (!show) return;

    if (configInicial) {
      setForm({
        tipo_habitacion_id: configInicial.tipo_habitacion_id,
        acomodacion_id: configInicial.acomodacion_id,
        cantidad: configInicial.cantidad,
      });
      catalogosApi.acomodacionesPermitidas(configInicial.tipo_habitacion_id).then(setAcomodaciones);
    } else {
      setForm(VACIO);
      setAcomodaciones([]);
    }
  }, [show, configInicial]);

  async function handleTipoChange(e) {
    const tipoId = e.target.value;
    setForm((f) => ({ ...f, tipo_habitacion_id: tipoId, acomodacion_id: '' }));
    if (tipoId) {
      const data = await catalogosApi.acomodacionesPermitidas(tipoId);
      setAcomodaciones(data);
    } else {
      setAcomodaciones([]);
    }
  }

  function handleChange(e) {
    const { id, value } = e.target;
    setForm((f) => ({ ...f, [id]: value }));
  }

  function handleSubmit(e) {
    e.preventDefault();
    onSubmit({
      tipo_habitacion_id: Number(form.tipo_habitacion_id),
      acomodacion_id: Number(form.acomodacion_id),
      cantidad: Number(form.cantidad),
    });
  }

  return (
    <Modal show={show} onHide={onHide}>
      <Form onSubmit={handleSubmit}>
        <Modal.Header closeButton className="navbar-decameron">
          <Modal.Title className="text-white">{configInicial ? 'Editar configuración' : 'Agregar configuración'}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <AlertMessage message={error?.message} errors={error?.errors} />

          <Form.Group className="mb-3">
            <Form.Label>Tipo de habitación</Form.Label>
            <Form.Select id="tipo_habitacion_id" value={form.tipo_habitacion_id} onChange={handleTipoChange} required>
              <option value="">Seleccione un tipo...</option>
              {tiposHabitacion.map((t) => (
                <option key={t.id} value={t.id}>{capitalizar(t.nombre)}</option>
              ))}
            </Form.Select>
          </Form.Group>
          <Form.Group className="mb-3">
            <Form.Label>Acomodación</Form.Label>
            <Form.Select id="acomodacion_id" value={form.acomodacion_id} onChange={handleChange} required disabled={!form.tipo_habitacion_id}>
              <option value="">
                {form.tipo_habitacion_id ? 'Seleccione una acomodación...' : 'Seleccione primero un tipo...'}
              </option>
              {acomodaciones.map((a) => (
                <option key={a.id} value={a.id}>{capitalizar(a.nombre)}</option>
              ))}
            </Form.Select>
            <Form.Text muted>Solo se muestran las acomodaciones válidas para el tipo elegido.</Form.Text>
          </Form.Group>
          <Form.Group>
            <Form.Label>Cantidad</Form.Label>
            <Form.Control id="cantidad" type="number" min={1} value={form.cantidad} onChange={handleChange} required />
          </Form.Group>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="outline-secondary" onClick={onHide}>Cancelar</Button>
          <Button type="submit" variant="primary">{configInicial ? 'Guardar cambios' : 'Agregar'}</Button>
        </Modal.Footer>
      </Form>
    </Modal>
  );
}
