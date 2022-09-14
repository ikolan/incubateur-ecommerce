<?php

namespace App\DtoHelper;

use App\Entity\Contact;

trait ContactDtoHelperTrait
{
    /**
     * Create a DTO from a contact.
     */
    public static function fromContact(Contact $contact): self
    {
        $output = new self();

        $output->id = $contact->getId();
        $output->firstName = $contact->getFirstName();
        $output->lastName = $contact->getLastName();
        $output->email = $contact->getEmail();
        $output->subject = $contact->getSubject();
        $output->message = $contact->getMessage();
        $output->sentAt = $contact->getSentAt();

        return $output;
    }

    /**
     * Transform a DTO instance to a new contact.
     */
    public function toContact(): Contact
    {
        $contact = new Contact();

        $contact->setFirstName($this->firstName);
        $contact->setLastName($this->lastName);
        $contact->setEmail($this->email);
        $contact->setSubject($this->subject);
        $contact->setMessage($this->message);
        $contact->setSentAt(new \DateTimeImmutable());

        return $contact;
    }
}
