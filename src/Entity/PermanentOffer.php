<?php

namespace App\Entity;

use App\Repository\PermanantOfferRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermanantOfferRepository::class)]
class PermanentOffer extends Offer
{

}
