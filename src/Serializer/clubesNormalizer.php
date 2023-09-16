<?php

namespace App\Serializer;

use App\Entity\Clubes;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class clubesNormalizer implements NormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer,
        private UrlHelper $urlHelper
    ) {
    }

    public function normalize($club, $format = null, array $context = []): ?array
    {
        $data = $this->normalizer->normalize($club, $format, $context);

        if (!empty($club->getEscudo())) {
            $data['escudo'] = $this->urlHelper->getAbsoluteUrl('/storage/default/' . $club->getEscudo());
        }
        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Clubes;
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