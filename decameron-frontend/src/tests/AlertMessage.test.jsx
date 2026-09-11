import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import AlertMessage from '../components/AlertMessage';

describe('AlertMessage', () => {
  it('no renderiza nada si no hay mensaje', () => {
    const { container } = render(<AlertMessage message={null} />);
    expect(container).toBeEmptyDOMElement();
  });

  it('muestra el mensaje principal', () => {
    render(<AlertMessage message="Ya existe un hotel con ese NIT." />);
    expect(screen.getByText('Ya existe un hotel con ese NIT.')).toBeInTheDocument();
  });

  it('muestra los errores por campo cuando existen', () => {
    render(
      <AlertMessage
        message="Datos inválidos."
        errors={{ nombre: ['El nombre es obligatorio.'] }}
      />
    );
    expect(screen.getByText('El nombre es obligatorio.')).toBeInTheDocument();
  });
});
