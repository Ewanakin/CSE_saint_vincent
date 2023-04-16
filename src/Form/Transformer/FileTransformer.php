<?php 

namespace App\Form\Transformer;

use App\Entity\Partner;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class FileTransformer implements DataTransformerInterface
{
    public function transform($link)
    {
        return $link;
    }

    public function reverseTransform($picture)
    {
        if ($picture != null) {
            $file = $picture->move('files/pictures', $picture->getClientOriginalName());
            return $file->getPathname();
        }
        return $picture;
    }
}