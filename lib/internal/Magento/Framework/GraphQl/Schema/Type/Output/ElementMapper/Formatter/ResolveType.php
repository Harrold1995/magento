<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Framework\GraphQl\Schema\Type\Output\ElementMapper\Formatter;

use Magento\Framework\GraphQl\Config\Element\InterfaceType;
use Magento\Framework\GraphQl\Config\Element\UnionType;
use Magento\Framework\GraphQl\Config\ConfigElementInterface;
use Magento\Framework\GraphQl\Schema\Type\OutputTypeInterface;
use Magento\Framework\GraphQl\Schema\Type\Output\ElementMapper\FormatterInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Add resolveType field to the schema config array based on 'type' config element.
 */
class ResolveType implements FormatterInterface
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @inheritDoc
     */
    public function format(ConfigElementInterface $configElement, OutputTypeInterface $outputType) : array
    {
        $config = [];
        if ($configElement instanceof InterfaceType || $configElement instanceof UnionType) {
            $typeResolver = $this->objectManager->get($configElement->getTypeResolver());
            $config['resolveType'] = function ($value) use ($typeResolver) {
                return $typeResolver->resolveType($value);
            };
        }

        return $config;
    }
}
