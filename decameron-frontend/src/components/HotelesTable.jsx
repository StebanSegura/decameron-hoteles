import Table from 'react-bootstrap/Table';
import Button from 'react-bootstrap/Button';
import ButtonGroup from 'react-bootstrap/ButtonGroup';
import Badge from 'react-bootstrap/Badge';

export default function HotelesTable({ hoteles, configuradasPorHotel, onVer, onEditar, onEliminar }) {
  if (!hoteles.length) {
    return <p className="text-center text-muted py-4">Aún no hay hoteles registrados.</p>;
  }

  return (
    <div className="table-responsive shadow-sm rounded">
      <Table hover className="align-middle mb-0" data-testid="tabla-hoteles">
        <thead className="table-decameron">
          <tr>
            <th>Nombre</th>
            <th>Ciudad</th>
            <th>NIT</th>
            <th className="text-center"># Habitaciones</th>
            <th className="text-center">Configuradas</th>
            <th className="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          {hoteles.map((h) => {
            const configuradas = configuradasPorHotel[h.id] ?? 0;
            const lleno = configuradas >= h.numero_habitaciones;

            return (
              <tr key={h.id}>
                <td>{h.nombre}</td>
                <td>{h.ciudad_nombre}</td>
                <td>{h.nit}</td>
                <td className="text-center">{h.numero_habitaciones}</td>
                <td className="text-center">
                  <Badge bg={lleno ? 'secondary' : 'light'} text={lleno ? undefined : 'dark'} className="border">
                    {configuradas} / {h.numero_habitaciones}
                  </Badge>
                </td>
                <td className="text-end">
                  <ButtonGroup size="sm">
                    <Button variant="outline-primary" onClick={() => onVer(h.id)}>Configurar</Button>
                    <Button variant="outline-secondary" onClick={() => onEditar(h.id)}>Editar</Button>
                    <Button variant="outline-danger" onClick={() => onEliminar(h.id)}>Eliminar</Button>
                  </ButtonGroup>
                </td>
              </tr>
            );
          })}
        </tbody>
      </Table>
    </div>
  );
}
