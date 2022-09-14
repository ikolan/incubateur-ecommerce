<?php

namespace App\Mailer;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailManager
{
    /** @var MailerInterface */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function getDefaultSender(): string
    {
        return $_ENV['MAIL_SENDER'];
    }

    public function getActivationLinkFromUser(User $user): string
    {
        return str_replace('{activationKey}', $user->getActivationKey(), $_ENV['MAIL_ACTIVATION_LINK']);
    }

    public function getForgotPasswordLinkFromUser(User $user): string
    {
        return str_replace('{resetKey}', $user->getResetKey(), $_ENV['MAIL_FORGOT_PASSWORD_LINK']);
    }

    public function sendEmail(string $to, string $subject, string $templatePath, array $templateContext)
    {
        $this->mailer->send((new TemplatedEmail())
            ->from($this->getDefaultSender())
            ->to($to)
            ->subject($subject)
            ->htmlTemplate('emails/'.$templatePath)
            ->context($templateContext));
    }
}
