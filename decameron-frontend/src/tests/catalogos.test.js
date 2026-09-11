import { describe, it, expect } from 'vitest';
import { capitalizar } from '../api/catalogos';

describe('capitalizar', () => {
  it('convierte SENCILLA a Sencilla', () => {
    expect(capitalizar('SENCILLA')).toBe('Sencilla');
  });

  it('convierte CUADRUPLE a Cuadruple', () => {
    expect(capitalizar('CUADRUPLE')).toBe('Cuadruple');
  });

  it('devuelve cadena vacía si no recibe texto', () => {
    expect(capitalizar('')).toBe('');
    expect(capitalizar(undefined)).toBe('');
  });
});
