import { api } from './client';

export const configuracionesApi = {
  listarPorHotel: (hotelId) => api.get(`/hoteles/${hotelId}/configuraciones`),
  crear: (hotelId, data) => api.post(`/hoteles/${hotelId}/configuraciones`, data),
  actualizar: (hotelId, configId, data) => api.put(`/hoteles/${hotelId}/configuraciones/${configId}`, data),
  eliminar: (hotelId, configId) => api.delete(`/hoteles/${hotelId}/configuraciones/${configId}`),
};
