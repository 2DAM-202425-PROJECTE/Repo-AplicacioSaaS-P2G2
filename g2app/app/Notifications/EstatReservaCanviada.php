<?php
namespace App\Notifications;

use App\Models\Reserva;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstatReservaCanviada extends Notification
{
    use Queueable;

    protected $reserva;
    private $nouEstat;

    public function __construct($reserva)
    {
        $this->reserva = $reserva;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Estat de la teva reserva actualitzat')
            ->greeting('Hola ' . $notifiable->name)
            ->line('L\'estat de la teva reserva al restaurant "' . $this->reserva->restaurant->nom . '" ha canviat.')
            ->line('Nou estat: ' . $this->formatEstat($this->reserva->estat))
            ->line('Data: ' . $this->reserva->data . ' a les ' . $this->reserva->hora)
            ->action('Veure reserva', url('/reserves'))
            ->line('Gràcies per confiar en nosaltres!');
    }

    protected function formatEstat($estat)
    {
        return match ($estat) {
            Reserva::PENDENT => 'Pendent',
            Reserva::CONFIRMAT => 'Confirmat',
            Reserva::CANCELAT => 'Cancel·lat',
            Reserva::COMPLETAT => 'Completat',
            default => 'Desconegut',
        };
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'missatge' => "L'estat de la teva reserva ha canviat a: " . $this->reserva->nom_estat,
            'reserva_id' => $this->reserva->id,
            'restaurant_id' => $this->reserva->restaurant_id,
            'estat' => $this->nouEstat,
            'data' => now()->toDateTimeString(),
        ];
    }

}
