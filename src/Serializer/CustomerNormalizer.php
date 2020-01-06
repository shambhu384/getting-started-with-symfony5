<?php

namespace App\Serializer;

use App\Entity\Customer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class CustomerNormalizer implements ContextAwareNormalizerInterface, ContextAwareDenormalizerInterface
{
    public function __construct(UrlGeneratorInterface $router, ObjectNormalizer $normalizer)
    {
        $this->router = $router;
        $this->normalizer = $normalizer;
    }

    public function normalize($topic, $format = null, array $context = [])
    {
        $context = ['groups' => 'public'];
        $data = $this->normalizer->normalize($topic, $format, $context);
        $data['firstName'] = 'hello' . $data['firstName'];

        return $data;
    }


    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $context = array_merge(['groups' => 'private'], $context);
        $data = $this->normalizer->denormalize($data, $type, $format, $context);
        return $data;
    }


    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Customer;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return class_exists($type);
    }
}
