import { api } from './client';

export const catalogosApi = {
  ciudades: () => api.get('/ciudades'),
  tiposHabitacion: () => api.get('/tipos-habitacion'),
  acomodaciones: () => api.get('/acomodaciones'),
  acomodacionesPermitidas: (tipoId) => api.get(`/tipos-habitacion/${tipoId}/acomodaciones`),
};

export function capitalizar(texto) {
  if (!texto) return '';
  return texto.charAt(0) + texto.slice(1).toLowerCase();
}
