<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailCodigo extends Mailable
{
    use Queueable, SerializesModels;

    public $codigo;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * compilador
     *
     * @return $this
     */
    public function build()
    {
        // correo de dominio para acceder a sendgrid y enviar el correo
        $address = 'info@panesingeniero.xyz';

        $subject = 'Recuperación de contraseña';
        $name = 'Panes_Inge_Metapan';

        return $this->from($address, $name)
            ->subject($subject)
            ->view('backend.correos.vistacorreocodigo')
            ->with([
                'codigo' => $this->codigo
            ]);
    }
}
