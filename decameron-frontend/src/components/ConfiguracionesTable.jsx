import Table from 'react-bootstrap/Table';
import Button from 'react-bootstrap/Button';
import ButtonGroup from 'react-bootstrap/ButtonGroup';
import { capitalizar } from '../api/catalogos';

export default function ConfiguracionesTable({ configuraciones, onEditar, onEliminar }) {
  if (!configuraciones.length) {
    return <p className="text-center text-muted py-4">Sin configuraciones aún.</p>;
  }

  return (
    <div className="table-responsive shadow-sm rounded">
      <Table hover className="align-middle mb-0" data-testid="tabla-configuraciones">
        <thead className="table-decameron">
          <tr>
            <th>Tipo de habitación</th>
            <th>Acomodación</th>
            <th className="text-center">Cantidad</th>
            <th className="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          {configuraciones.map((c) => (
            <tr key={c.id}>
              <td>{capitalizar(c.tipo_habitacion_nombre)}</td>
              <td>{capitalizar(c.acomodacion_nombre)}</td>
              <td className="text-center">{c.cantidad}</td>
              <td className="text-end">
                <ButtonGroup size="sm">
                  <Button variant="outline-secondary" onClick={() => onEditar(c)}>Editar</Button>
                  <Button variant="outline-danger" onClick={() => onEliminar(c.id)}>Eliminar</Button>
                </ButtonGroup>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>
    </div>
  );
}
