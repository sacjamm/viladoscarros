<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadSimulacaoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $mensagemHtml;

    public function __construct($mensagemHtml)
    {
        $this->mensagemHtml = $mensagemHtml;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Credere LEADS')
                    ->from('viladoscarrosmkt@gmail.com', 'Vila dos Carros')
                    ->html($this->mensagemHtml);
        //return $this->view('view.name');
    }
}
