<x-mail::message>
# ¡Un alumno ha terminado un nuevo pedimento!

El alumno **{{ $pedimento->usuario->nombre }}** ha terminado y registrado el pedimento número **{{ $pedimento->num_pedimento }}**.

**Detalles del Pedimento:**
- **Tipo de Operación:** {{ $pedimento->tipo_operacion }}
- **Importador:** {{ $pedimento->nombre_importador }}
- **Aduana:** {{ $pedimento->aduana_entrada_salida }}

Puedes revisar los detalles del pedimento en la plataforma accediendo a tu cuenta.

<x-mail::button :url="route('pedimentos.show', $pedimento)">
Ver Pedimento
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
