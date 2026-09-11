import { describe, it, expect, vi } from 'vitest';
import { render, screen } from '@testing-library/react';
import HotelesTable from '../components/HotelesTable';

const hoteles = [
  { id: 1, nombre: 'DECAMERON CARTAGENA', ciudad_nombre: 'CARTAGENA', nit: '12345678-9', numero_habitaciones: 42 },
];

describe('HotelesTable', () => {
  it('muestra un mensaje cuando no hay hoteles', () => {
    render(<HotelesTable hoteles={[]} configuradasPorHotel={{}} onVer={vi.fn()} onEditar={vi.fn()} onEliminar={vi.fn()} />);
    expect(screen.getByText(/aún no hay hoteles registrados/i)).toBeInTheDocument();
  });

  it('renderiza la fila de un hotel con su ocupación', () => {
    render(
      <HotelesTable
        hoteles={hoteles}
        configuradasPorHotel={{ 1: 30 }}
        onVer={vi.fn()}
        onEditar={vi.fn()}
        onEliminar={vi.fn()}
      />
    );

    expect(screen.getByText('DECAMERON CARTAGENA')).toBeInTheDocument();
    expect(screen.getByText('30 / 42')).toBeInTheDocument();
  });
});
