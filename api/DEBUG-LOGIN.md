# Si el login en la app sigue fallando

## 1. Mira la consola de la API

Con la API en marcha (`node index.js`), intenta iniciar sesión en la app. En la terminal donde corre la API deberías ver algo como:

- `[LOGIN] Intento: admin@gmail.com` → la petición está llegando.
- Si **no aparece nada** → la app no llega a la API (URL o red).

Mensajes posibles después del intento:

- `No existe cliente activo con ese correo` → no hay ese correo en la tabla **clientes** o no está activo.
- `Hash de contraseña inválido o truncado` → la columna `clave` está mal. Ejecuta: `npm run create-client`.
- `Contraseña incorrecta` → el correo existe pero la contraseña no coincide. Ejecuta: `npm run create-client` para poner contraseña `Password`.
- `OK: admin@gmail.com` → login correcto (si aun así la app falla, es tema de red/app).

## 2. Revisa la URL en la app (ApiConfig.kt)

- **Emulador:** `http://10.0.2.2:3000` suele ser correcto.
- **Móvil físico:** tiene que ser la IP de tu PC, por ejemplo `http://192.168.1.5:3000`.
  - En la PC: abre CMD y escribe `ipconfig`. Usa la "Dirección IPv4" de la red por la que va el móvil (WiFi).
  - En `Movil/app/.../ApiConfig.kt` cambia `BASE_URL` a esa IP y el puerto (3000).

## 3. Vuelve a crear el cliente de prueba

En la carpeta de la API:

```bash
npm run create-client
```

Luego inicia sesión en la app con:

- **Usuario:** admin@gmail.com  
- **Contraseña:** Password  

## 4. Comprobar que la app alcanza la API

En el navegador del móvil o del emulador abre:

- Emulador: `http://10.0.2.2:3000/api/auth/ping`
- Móvil: `http://IP_DE_TU_PC:3000/api/auth/ping`

Si ves `{"ok":true,"msg":"API alcanzable"}` la API se alcanza; si no, el problema es la URL o el firewall.
