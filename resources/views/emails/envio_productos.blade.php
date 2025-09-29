<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OwnBrand</title>
    <style>
        /* Reset básico */
        body,
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f6fa;
            color: #4a5568;
            line-height: 1.5;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 15px;
        }

        /* Card principal */
        .card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            padding: 30px 25px;
        }

        /* Header */
        .header img {
            width: 100%;
            object-fit: cover;
            height: 10rem;
            border-radius: 16px;
            margin-bottom: 25px;
        }

        h1 {
            font-size: 28px;
            color: #2d3748;
            margin-bottom: 15px;
            text-align: center;
        }

        h3 {
            font-size: 18px;
            color: #1a202c;
            margin-bottom: 10px;
            text-align: center;
        }

        .texto p {
            font-size: 16px;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Productos */
        .productos {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }

        .producto {
            background: #f7fafc;
            border-radius: 12px;
            width: 180px;
            padding: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.2s;
        }

        .producto:hover {
            transform: translateY(-4px);
        }

        .producto img {
            width: 100%;
            height: 120px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .producto p {
            margin: 5px 0;
        }

        .producto strong {
            color: #2d3748;
            font-size: 14px;
        }

        .producto .precio {
            font-weight: bold;
            color: #e53e3e;
        }

        /* Botón principal */
        .button {
            display: block;
            background: linear-gradient(135deg, #f8b500, #fceabb);
            color: #2d3748;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            padding: 14px 25px;
            border-radius: 12px;
            text-decoration: none;
            margin: 25px auto 0 auto;
            max-width: 250px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: background 0.3s, transform 0.2s;
        }

        .button:hover {
            background: linear-gradient(135deg, #fceabb, #f8b500);
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 13px;
            color: #a0aec0;
            margin-top: 30px;
        }

        /* Responsive */
        @media screen and (max-width: 400px) {
            .productos {
                flex-direction: column;
                align-items: center;
            }

            .producto {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <img src="https://images.pexels.com/photos/996329/pexels-photo-996329.jpeg" alt="Moda OwnBrand">
            </div>

            <div class="texto">
                <h1>¡Hola {{ $nombre }}!</h1>
                <p>🎉 ¡Felicidades! Has recibido tu <strong>caja</strong> con prendas seleccionadas según tu estilo y
                    preferencias.</p>
            </div>

            <h3>Tus productos seleccionados:</h3>
            <div class="productos">
                @foreach ($productos as $producto)
                <div class="producto">
                    <img src="{{ $producto['image'] }}" alt="{{ $producto['title'] }}">
                    <p><strong>{{ $producto['title'] }}</strong></p>
                    <p>{{ $producto['category'] }}</p>
                    <p class="precio">${{ number_format($producto['price'], 2) }}</p>
                </div>
                @endforeach
            </div>

            <a href="https://ownbrandproject.onrender.com/planes" class="button">Descubre más cajas</a>

            <p class="footer">Gracias por confiar en <strong>{{ config('app.name') }}</strong>.</p>
        </div>
    </div>
</body>

</html>