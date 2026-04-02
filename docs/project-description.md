# Quiniela

- Quiniela es un tipo de juego de pronosticos donde los usuarios intentan adivinar el resultado de una serie de partidos de fútbol. Por cada acierto en la jugada suman puntos. Al final los usuarios con más puntos ganan.

## Tipos

- Hay 2 tipos de formas de adivinar el resultado:
    - Por resultado: El usuario predice si gana el equipo 1, equipo 2 o empate.
        - Suma 1 punto por cada acierto
    - Por marcador: El usuario predice el marcador exacto de cada partido.
        - Acierta marcador exacto: más 4 puntos
        - Acierta resultado (gana equipo 1, equipo 2 o empate): más 2 puntos
        - Desacierto: - 1 puntos

- La cantidad de puntos que se suma pueden ser configurable

## Jugada / Ticket

- Cada jugada tiene un costo. Ex: $1.00. Debe ser configurable para cada quiniela.

- El usuario podrá cambiar los pronósticos de su ticket mientras no haya cerrado la quiniela.

## Premios

- El premio a repartir es un monto monetario. Las posiciones que reciben el premio se hacen de forma arbitraria, es decir, el premio puede ser  repartido para los primeros 2 o 3 posiciones, o bien solo para el 1er puesto.

### Tipos de premios

- Monto Fijo A repartir. Se define un monto el cual se reparte en porcentaje para las posiciones ganadoras. Ejemplo: Monto de $100 a repartir, $80 para el primer lugar y $20 par el segundo lugar.

- Monto por porcentaje de lo jugado. Cada posicion recibe un monto de premio igual a un porcentaje del total jugado. Ejemplo: Se repartira el 60% de lo jugado, 40% al primer lugar y 20% al segundo lugar. En ese caso si el monto jugado es $100, le toca $40 al primer puesto y $20 al segundo.

## Balance

- Los usuarios tendrán un balance para realizar sus jugadas.

- Los premios de las quinielas son acréditos que se suman al balance del usuario.

- A nivel de admin se debe tener un historial de balance para cada usuario.

- Se debe tener un historial de movimientos de balance para cada usuario.


## Depósitos

- Los usuario puede sumar dinero a su balance a través de depósitos.

- Inicialmente se trabajará con binance pay como metodo de pago. Estos serán procesados de manera manual.

- Tener en cuenta que en un futuro se incorporarán otros métodos de pago como cripto, pago móvil, airtm ( método de pago en Venezuela), paypal... y pueden ser procesados de manera manual o automática.

- Tener en cuenta que los campos necesarios para cada método de pago varía, consideralo para hacer el módulo de pagos en depósitos y retiros fácilmente modificable y escalable. Ejemplos:
  - Binance pay: requiere binance ID o correo
  - CRIPTO: Dirección de pago
  - Pago móvil: requiere télefono, cédula y banco

## Retiros

- Mismos consideraciones que los depósitos.

## Stack
Laravel 13
Filament 5
Livewire
Sqlite

## packages

bavix/laravel-wallet
