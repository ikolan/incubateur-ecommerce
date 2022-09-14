<?php

namespace App\DtoHelper;

use App\Entity\Address;

trait AddressDtoHelperTrait
{
    /**
     * Create a DTO from an address.
     */
    public static function fromAddress(Address $address): self
    {
        $output = new self();

        $output->id = $address->getId();
        $output->title = $address->getTitle();
        $output->number = $address->getNumber();
        $output->road = $address->getRoad();
        $output->zipcode = $address->getZipcode();
        $output->city = $address->getCity();
        $output->phone = $address->getPhone();

        return $output;
    }

    /**
     * Transform to a new address.
     *
     * @param $base If not null, use an already created address as base
     */
    public function toAddress(Address $base = null): Address
    {
        $address = $base ?? new Address();

        $address->setTitle($this->title);
        $address->setNumber($this->number);
        $address->setRoad($this->road);
        $address->setZipcode($this->zipcode);
        $address->setCity($this->city);
        $address->setPhone($this->phone);

        return $address;
    }
}
