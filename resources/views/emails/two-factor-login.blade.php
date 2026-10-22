<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tu código de verificación</title>
</head>
<body style="margin:0;background:#f4f6f8;color:#243247;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:560px;margin:32px auto;padding:32px;background:#ffffff;border:1px solid #e5e9ef;border-radius:12px;">
        <h1 style="margin-top:0;color:#174ea6;font-size:24px;">{{ config('app.name') }}</h1>
        <p>Hola,</p>
        <p>Tu código de verificación es:</p>
        <p style="margin:28px 0;text-align:center;font-size:32px;font-weight:bold;letter-spacing:8px;color:#174ea6;">
            {{ $code }}
        </p>
        <p>Este código vence en 5 minutos. No lo compartas con nadie.</p>
        <p>Si no intentaste iniciar sesión, puedes ignorar este mensaje.</p>
        <p style="color:#697586;font-size:13px;">Este es un mensaje automático.</p>
    </div>
</body>
</html>
