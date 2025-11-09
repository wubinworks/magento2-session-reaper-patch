<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\SessionReaperPatch\Plugin\Framework\Webapi;

use Magento\Framework\Reflection\TypeProcessor;
use Magento\Framework\ObjectManager\ConfigInterface as ObjectManagerConfigInterface;

/**
 * Patch for CVE-2025-54236(a.k.a Session Reaper)
 *
 * @link https://helpx.adobe.com/security/products/magento/apsb25-88.html
 * @link https://experienceleague.adobe.com/en/docs/experience-cloud-kcs/kbarticles/ka-27397
 */
class ServiceInputProcessor
{
    /**
     * @var TypeProcessor
     */
    private $typeProcessor;

    /**
     * @var ObjectManagerConfigInterface
     */
    private $objectManagerConfig;

    /**
     * Constructor
     *
     * @param TypeProcessor $typeProcessor
     * @param ObjectManagerConfigInterface $objectManagerConfig
     */
    public function __construct(
        TypeProcessor $typeProcessor,
        ObjectManagerConfigInterface $objectManagerConfig
    ) {
        $this->typeProcessor = $typeProcessor;
        $this->objectManagerConfig = $objectManagerConfig;
    }

    /**
     * Before plugin for filtering unsafe constructor data
     *
     * @param \Magento\Framework\Webapi\ServiceInputProcessor $subject
     * @param mixed $data
     * @param string $type
     * @return array|null
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeConvertValue(
        \Magento\Framework\Webapi\ServiceInputProcessor $subject,
        $data,
        $type
    ) {
        if (!is_array($data)
            || empty($data)
            || $this->typeProcessor->isTypeSimple($type)
            || $this->typeProcessor->isTypeAny($type)
        ) {
            return null;
        }

        if ($this->typeProcessor->isArrayType($type)) { // E.g., \Class[]
            $itemType = $this->typeProcessor->getArrayItemType($type);
            foreach ($data as $key => $item) {
                $sanitizedData[$key] = $this->_sanitizeConstructorData((string)$itemType, $item);
            }
        } else {
            $sanitizedData = $this->_sanitizeConstructorData((string)$type, $data);
        }

        return [$sanitizedData, $type];
    }

    /**
     * Sanitize constructor data
     *
     * @param string $className
     * @param array $data
     *
     * @return array
     */
    private function _sanitizeConstructorData(string $className, array $data): array
    {
        $class = $this->_createClassReflection($this->_getPreferenceClass($className) ?: $className);
        try {
            $constructor = $class->getMethod('__construct');
        } catch (\ReflectionException $e) {
            return $data;
        }

        foreach ($constructor->getParameters() as $parameter) {
            $parameterType = $this->typeProcessor->getParamType($parameter);
            if (!$this->typeProcessor->isTypeSimple($parameterType)
                && 1 != preg_match('~\\\\?\w+\\\\\w+\\\\Api\\\\Data\\\\~', $parameterType)
            ) {
                unset($data[$parameter->getName()]);
            }
        }

        return $data;
    }

    /**
     * Get preference class
     *
     * @param string $className
     *
     * @return string
     */
    private function _getPreferenceClass(string $className): string
    {
        $preferenceClass = (string)$this->objectManagerConfig->getPreference($className);
        $suffixes = ['\Interceptor', '\Proxy'];
        foreach ($suffixes as $suffix) {
            if (substr($preferenceClass, -strlen($suffix)) === $suffix) {
                $preferenceClass = substr($preferenceClass, 0, -strlen($suffix));
            }
        }

        return $preferenceClass;
    }

    /**
     * Create class reflection
     *
     * For Magento 2.3 backward compatibility
     *
     * @param string $className
     *
     * @return \Laminas\Code\Reflection\ClassReflection|\Zend\Code\Reflection\ClassReflection
     */
    private function _createClassReflection(string $className): \ReflectionClass
    {
        if (class_exists(\Laminas\Code\Reflection\ClassReflection::class)) {
            $classReflection = \Laminas\Code\Reflection\ClassReflection::class;
        } else {
            $classReflection = \Zend\Code\Reflection\ClassReflection::class;
        }

        return new $classReflection($className);
    }
}
