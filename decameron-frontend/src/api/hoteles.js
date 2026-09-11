import { api } from './client';

export const hotelesApi = {
  listar: () => api.get('/hoteles'),
  obtener: (id) => api.get(`/hoteles/${id}`),
  crear: (data) => api.post('/hoteles', data),
  actualizar: (id, data) => api.put(`/hoteles/${id}`, data),
  eliminar: (id) => api.delete(`/hoteles/${id}`),
};
