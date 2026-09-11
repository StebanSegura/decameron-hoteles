import Alert from 'react-bootstrap/Alert';

/**
 * Muestra un mensaje de error/éxito, con lista opcional de errores por
 * campo (tal como los devuelve Laravel en `errors`).
 */
export default function AlertMessage({ variant = 'danger', message, errors = {} }) {
  if (!message) return null;

  const detalles = Object.values(errors || {}).flat();

  return (
    <Alert variant={variant === 'error' ? 'danger' : variant}>
      <strong>{message}</strong>
      {detalles.length > 0 && (
        <ul className="mb-0 mt-1">
          {detalles.map((d, i) => (
            <li key={i}>{d}</li>
          ))}
        </ul>
      )}
    </Alert>
  );
}
