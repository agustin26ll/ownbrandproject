<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnvioProductosMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $productos;
    public $ocupacion;
    public $edad;

    public function __construct(array $data)
    {
        $this->nombre = $data['nombre'];
        $this->productos = $data['productos'];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu caja de OwnBrand',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.envio_productos',
            with: [
                'nombre' => $this->nombre,
                'productos' => $this->productos,
                'ocupacion' => $this->ocupacion,
                'edad' => $this->edad,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
