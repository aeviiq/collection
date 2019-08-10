<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;

abstract class AbstractObjectCollection extends AbstractCollection
{
    /**
     * {@inheritDoc}
     */
    public function natcasesort(): void
    {
        $this->throwExceptionIfToStringDoesNotExists();
        parent::natcasesort();
    }

    /**
     * {@inheritDoc}
     */
    public function natsort(): void
    {
        $this->throwExceptionIfToStringDoesNotExists();
        parent::natsort();
    }

    /**
     * @return string The allowed class instance this collection supports.
     */
    abstract protected function allowedInstance(): string;

    /**
     * {@inheritDoc}
     */
    protected function validateElement($element): void
    {
        if (!\is_object($element)) {
            throw InvalidArgumentException::expectedObject($this, \gettype($element));
        }

        $allowedInstance = $this->allowedInstance();
        if (!($element instanceof $allowedInstance)) {
            throw InvalidArgumentException::expectedInstance($this, $allowedInstance, \get_class($element));
        }
    }

    /**
     * @throws LogicException
     */
    protected function throwExceptionIfToStringDoesNotExists(): void
    {
        $class = $this->allowedInstance();
        if (!(new \ReflectionClass($class))->hasMethod('__toString')) {
            throw new LogicException(\sprintf('"%s" must implement __toString() in order to natsort().', $class));
        }
    }

    /**
     * @see https://github.com/aeviiq/collection/issues/19
     */
    public function __clone()
    {
        $this->getStorage()->setFlags(\ArrayObject::ARRAY_AS_PROPS);
        if ($this->suppressDeepCloneValidation()) {
            return;
        }

        foreach ($this->getKeys() as $key) {
            if (!\is_string($key) || !\ctype_alnum(\str_replace('_', '', $key))) {
                throw new LogicException(\sprintf(
                    'In order to correctly clone an object collection, all keys must be strings that are valid property names as defined by PHP.' .
                    ' If you are not deep cloning this collection, you could choose to suppress this exception by overriding AbstractObjectCollection#suppressDeepCloneValidation()'
                ));
            }
        }
    }

    /**
     * @see https://github.com/aeviiq/collection/issues/19
     *
     * @return bool Whether or not an exception should be thrown when this collection is getting
     *              cloned and has invalid property names. When invalid keys are used, this
     *              could cause referential bugs because of how PHP ArrayObject handles the ARRAY_AS_PROPS
     *              option in combination with 'invalid' keys. Using any deep clone, the object elements
     *              inside the storage will *not* be detected and be ignore. This could cause unexpected
     *              results and bugs.
     */
    protected function suppressDeepCloneValidation(): bool
    {
        return false;
    }
}
