<?php

namespace App\Swagger;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Yaml\Yaml;
use ArrayObject;

final class SwaggerDecorator implements NormalizerInterface
{
    private $decorated;

    private const ADDITIONAL_ROUTES_YAML_FOLDER = 'additional_routes';

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        return $this->parseAdditionRoutesYamlFiles($this->decorated->normalize($object, $format, $context));
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }


    public function parseAdditionRoutesYamlFiles(array $docs)
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__ .'/' . self::ADDITIONAL_ROUTES_YAML_FOLDER);
        /** @var \ArrayObject $paths */

        foreach ($finder as $file) {
            $fileContent = Yaml::parseFile($file);
            $docs['paths'] = new ArrayObject(
                array_merge($docs['paths']->getArrayCopy(), $fileContent)
            );
        }

        return $docs;
    }
}