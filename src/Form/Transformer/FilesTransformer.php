<?php

namespace App\Form\Transformer;

use App\Entity\OfferPicture;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilesTransformer implements DataTransformerInterface
{
    public array $links = [];

    public function transform(mixed $value)
    {
        return $value;
    }

    public function reverseTransform(mixed $value)
    {
        if(is_a($value, UploadedFile::class))
        {
            dump($value);
            $file = $value->move('files/offer/pictures/', $value->getClientOriginalName());
            $offerPicture = new OfferPicture();
            $offerPicture->setLink($file->getPathname());
            array_push($this->links, $offerPicture);
        }
        else
        {
            return $this->links;
        }
    }
}