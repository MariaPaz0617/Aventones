@component('mail::message')
    # Recordatorio de Reserva Pendiente

    Hola **{{ $reserva->ride->usuario->nombre }}**,  
    Tienes una solicitud de reserva pendiente para tu ride:

    **{{ $reserva->ride->lugar_salida }} → {{ $reserva->ride->lugar_llegada }}**  
    Fecha: **{{ $reserva->ride->fecha }}**  
    Hora: **{{ $reserva->ride->hora }}**

    El pasajero:  
    **{{ $reserva->pasajero->nombre }} {{ $reserva->pasajero->apellido }}**

    Solicitó **{{ $reserva->cantidad_asientos }} asiento(s)**.

    Por favor revisa la solicitud en tu panel de chofer.

    Gracias,  
    El equipo de Aventones
@endcomponent
