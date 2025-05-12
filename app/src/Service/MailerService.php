<?php


namespace App\Service;

use App\Entity\User;
use App\Entity\Trip;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerService
{
    public function __construct(
        private MailerInterface $mailer,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function sendRatingRequest(User $user, Trip $trip): void
    {
        $link = $this->urlGenerator->generate(
            'app_feedback', 
            ['id' => $trip->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $email = (new Email())
            ->from('noreply@ecoride.fr')
            ->to($user->getEmail())
            ->subject('Notez votre trajet sur EcoRide')
            ->html("
                <p>Bonjour {$user->getUsername()},</p>
                <p>Votre trajet du {$trip->getDepartureDatetime()->format('d/m/Y à H:i')} de {$trip->getDepartureCity()} à {$trip->getArrivalCity()} est terminé.</p>
                <p><a href='$link'>Cliquez ici pour confirmer et donner votre avis</a></p>
                <p>Merci pour votre confiance !</p>
            ");

        $this->mailer->send($email);
    }

    public function sendCancelTrip(Trip $trip, User $user){
        

        $email =(new Email())
            ->from('noreply@ecoride.fr')
            ->to($user->getEmail())
            ->subject('annulation de trajet sur EcoRide')
            ->html("<p>Bonjour {$user->getUsername()},</p>
            <p>Malheureusement votre trajet du {$trip->getDepartureDatetime()->format('d/m/Y à H:i')} de {$trip->getDepartureCity()} à {$trip->getArrivalCity()} est annulé.</p>
            ");

            $this->mailer->send($email);
    }
}
