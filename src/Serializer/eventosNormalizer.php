<?php

namespace App\Serializer;

use App\Entity\Eventos;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class eventosNormalizer implements NormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer
    ) {
    }

    public function normalize($evento, $format = null, array $context = []): ?array
    {
        $data = $this->normalizer->normalize($evento, $format, $context);

        if (!empty($evento->getHoraIni())) {
            $data['horaIni'] = $evento->getHoraIni()->format('G:i');
        }
        if (!empty($evento->getHoraFin())) {
            $data['horaFin'] = $evento->getHoraFin()->format('G:i');
        }     
        
        if (!empty($evento->getFecha())) {
            $data['fecha'] = $evento->getFecha()->format('d-m-Y');
        }   

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Eventos;
    }

    public function getSupportedTypes(?string $format): ?array
    {
        return [];
        // TODO: Implement getSupportedTypes() method.
    }

    // delete this removing the 'implements CacheableSupportsMethodInterface'
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}