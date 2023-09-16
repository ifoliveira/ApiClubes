<?php

namespace App\Serializer;

use App\Entity\Jugador;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class jugadorNormalizer implements NormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer,
        private UrlHelper $urlHelper
    ) {
    }

    public function normalize($jugador, $format = null, array $context = []): ?array
    {
        $data = $this->normalizer->normalize($jugador, $format, $context);

        if (!empty($jugador->getFoto())) {
            $data['foto'] = $this->urlHelper->getAbsoluteUrl('/storage/default/' . $jugador->getFoto());
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Jugador;
    }

    public function getSupportedTypes(?string $format)
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