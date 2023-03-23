<?php 

namespace App\Form\Transformer;

use App\Entity\Partner;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class FileTransformer implements DataTransformerInterface
{
    public function transform($link): ?File
    {
        $partner = new File($link);
        return $partner;        
    }

    public function reverseTransform($picture)
    {
        $file = $picture->move('files/pictures', $picture->getClientOriginalName());
        return $file->getPathname();
    }
}