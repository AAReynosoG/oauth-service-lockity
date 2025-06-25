# Autenticación OAuth 2.0 con PKCE

---
### Generar .env
Tanto para MacOs, Linux y Windows (PowerShell)
```
cp .env.example .env
```

---

### Dependencias
 `Laravel Passport`.
 ```
 composer require laravel/passport
 ```

### Base de Datos
**Importante** En lugar de ejecutar las migraciones que vienen en el proyecto, 
 se deben ejecutar las sentencias SQL que vienen en el archivo `lockity_tables.txt`, de esa manera se puede tener la base de datos completa en entorno local.

### Registrar cliente y generar client_id
Para registrar un nuevo cliente OAuth y generar un client_id, utiliza el siguiente comando:
```
php artisan passport:client --public
```

* Genera automáticamente un client_id.
* Te pedirá ingresar un nombre para identificar el cliente.
* Solicitará la URL de redirección (redirect_uri) que usará el frontend para recibir el código de autorización.

---
## Flujo general

1. El frontend redirige al usuario al endpoint `/oauth/authorize`, pasando una serie de parámetros en la URL.
2. El usuario inicia sesión y autoriza el acceso.
3. El backend redirecciona al `redirect_uri`, incluyendo un `code` y el `state` como query params.
4. El frontend toma ese `code` y lo usa para obtener un `access_token` en el endpoint `/oauth/token`.

---

## Paso 1: Redirección a `/oauth/authorize`

El frontend debe construir una URL como la siguiente:

```ts
  `https://backend.com/oauth/authorize?response_type=code&client_id=${clientId}&redirect_uri=${encodeURIComponent(redirectUri)}&scope=&state=${state}&code_challenge=${codeChallenge}&code_challenge_method=S256`;
```

### Parámetros query obligatorios

| Parámetro               | Descripción                                                                                                          |
| ----------------------- |----------------------------------------------------------------------------------------------------------------------|
| `response_type=code`    | Indica que se solicita un código de autorización.                                                                    |
| `client_id`             | ID público del cliente OAuth (asignado por el backend).                                                              |
| `redirect_uri`          | URL del frontend a la que el backend redirigirá al finalizar el login. Debe coincidir exactamente con la registrada. |
| `scope`                 | Permisos solicitados (vacío ya que no se usa).                                                              |
| `state`                 | Cadena aleatoria generada por el frontend para prevenir ataques CSRF.                                                |
| `code_challenge`        | Resultado de aplicar SHA256 + base64url al `code_verifier` generado previamente.                                     |
| `code_challenge_method` | Método de hashing utilizado. Debe ser `S256`.                                                                        |

---

## Paso 2: Redirección del backend al frontend

Después del login exitoso, el backend redirigirá al `redirect_uri` con los siguientes **query params**:

```url
https://frontend.com/callback?code=code1234&state=XYZ789
```

El frontend debe:

1. Validar que el `state` recibido coincida con el `state` que generó originalmente.
2. Guardar temporalmente el valor del `code` para el siguiente paso.

---

## Paso 3: Solicitud a `/oauth/token`

El frontend realiza una petición `POST` al endpoint `/oauth/token` usando `application/x-www-form-urlencoded` con los siguientes parámetros:

| Campo           | Valor                                                                                |
| --------------- | ------------------------------------------------------------------------------------ |
| `grant_type`    | `authorization_code`                                                                 |
| `client_id`     | El mismo que en el paso 1                                                            |
| `redirect_uri`  | Debe coincidir con el usado anteriormente                                            |
| `code`          | El código de autorización recibido del backend                                       |
| `code_verifier` | La cadena secreta original generada al inicio (antes de generar el `code_challenge`) |

El backend verificará el `code_verifier` comparándolo con el `code_challenge` recibido al inicio del flujo.

---

## Explicación técnica de los campos clave

### `code_verifier`

Una cadena aleatoria segura, generada por el frontend. Debe tener entre 43 y 128 caracteres. Ejemplo:

```
Y3pLwPf8dEegWD7MRY4FG2oETT8T2xPpiq0q0vXr6m8
```

Se guarda localmente y **nunca se envía al backend en el primer paso**. Se usará más adelante.

---

### `code_challenge`

Se obtiene aplicando `SHA256` al `code_verifier` y luego codificando el resultado en **base64 URL-safe** (sin padding, sin caracteres especiales).

```js
// Ejemplo en JS
const hashed = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(codeVerifier));
const base64url = btoa(String.fromCharCode(...new Uint8Array(hashed)))
  .replace(/\+/g, '-')
  .replace(/\//g, '_')
  .replace(/=+$/, '');
```

---

### `code_challenge_method`

Debe ser `S256`. Esto indica al backend que el `code_challenge` se generó usando `SHA256`, no `plain`.

---

### `state`

Cadena aleatoria generada por el frontend para proteger contra ataques CSRF. Debe ser validada cuando se recibe de vuelta. Por ejemplo:

```js
const state = crypto.randomUUID();
sessionStorage.setItem('oauth_state', state);
```

Y luego:

```js
const returnedState = new URLSearchParams(window.location.search).get('state');
const originalState = sessionStorage.getItem('oauth_state');

if (returnedState !== originalState) {
  throw new Error("Possible CSRF attack: 'state' mismatch.");
}
```

---

## Resultado esperado

Si todo es correcto, el backend responderá con:

```json
{
  "access_token": "access_token_generado",
  "refresh_token": "refresh_token_generado",
  "token_type": "Bearer",
  "expires_in": 3600
}
```

---

## Notas adicionales
* El `code` solo puede usarse una vez.
* El `code_verifier` **debe ser el mismo** que se usó para generar el `code_challenge`.
