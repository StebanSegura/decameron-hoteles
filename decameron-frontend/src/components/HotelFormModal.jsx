import { useEffect, useState } from 'react';
import Modal from 'react-bootstrap/Modal';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import AlertMessage from './AlertMessage';

const VACIO = { nombre: '', nit: '', direccion: '', ciudad_id: '', numero_habitaciones: '' };

export default function HotelFormModal({ show, onHide, onSubmit, ciudades, hotelInicial, error }) {
  const [form, setForm] = useState(VACIO);

  useEffect(() => {
    if (show) {
      setForm(
        hotelInicial
          ? {
              nombre: hotelInicial.nombre,
              nit: hotelInicial.nit,
              direccion: hotelInicial.direccion,
              ciudad_id: hotelInicial.ciudad_id,
              numero_habitaciones: hotelInicial.numero_habitaciones,
            }
          : VACIO
      );
    }
  }, [show, hotelInicial]);

  function handleChange(e) {
    const { id, value } = e.target;
    setForm((f) => ({ ...f, [id]: value }));
  }

  function handleSubmit(e) {
    e.preventDefault();
    onSubmit({
      ...form,
      ciudad_id: Number(form.ciudad_id),
      numero_habitaciones: Number(form.numero_habitaciones),
    });
  }

  return (
    <Modal show={show} onHide={onHide}>
      <Form onSubmit={handleSubmit}>
        <Modal.Header closeButton className="navbar-decameron">
          <Modal.Title className="text-white">{hotelInicial ? 'Editar hotel' : 'Nuevo hotel'}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <AlertMessage message={error?.message} errors={error?.errors} />

          <Form.Group className="mb-3">
            <Form.Label>Nombre del hotel</Form.Label>
            <Form.Control id="nombre" value={form.nombre} onChange={handleChange} maxLength={150} required placeholder="DECAMERON CARTAGENA" />
          </Form.Group>
          <Form.Group className="mb-3">
            <Form.Label>NIT</Form.Label>
            <Form.Control id="nit" value={form.nit} onChange={handleChange} maxLength={30} required placeholder="12345678-9" />
          </Form.Group>
          <Form.Group className="mb-3">
            <Form.Label>Dirección</Form.Label>
            <Form.Control id="direccion" value={form.direccion} onChange={handleChange} maxLength={200} required placeholder="CALLE 23 58-25" />
          </Form.Group>
          <Form.Group className="mb-3">
            <Form.Label>Ciudad</Form.Label>
            <Form.Select id="ciudad_id" value={form.ciudad_id} onChange={handleChange} required>
              <option value="">Seleccione una ciudad...</option>
              {ciudades.map((c) => (
                <option key={c.id} value={c.id}>{c.nombre}</option>
              ))}
            </Form.Select>
          </Form.Group>
          <Form.Group>
            <Form.Label>Número máximo de habitaciones</Form.Label>
            <Form.Control id="numero_habitaciones" type="number" min={1} value={form.numero_habitaciones} onChange={handleChange} required placeholder="42" />
          </Form.Group>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="outline-secondary" onClick={onHide}>Cancelar</Button>
          <Button type="submit" variant="primary">Guardar hotel</Button>
        </Modal.Footer>
      </Form>
    </Modal>
  );
}
