<?php declare(strict_types=1);

namespace Mcneely\Collections;

class LazyHydrationCollection extends AbstractCollection
{
    /** @var string $hydratedClass */
    protected $hydratedClass = '';

    /**
     * @param string $hydratedClass
     *
     * @return self
     */
    public function setClass(string $hydratedClass): self
    {
        $this->hydratedClass = $hydratedClass;

        return $this;
    }

    public function setHydrator(callable $hydrator, bool $useGenerator = false): self
    {
        $class     = $this->hydratedClass;
        $retriever = function ($object) use ($hydrator, $class) {
            $return = [];
            foreach ($object as $key => $value) {
                $object       = (!empty($class)) ? new $class() : false;
                $value        = call_user_func_array($hydrator, [$value, $object]);
                $return[$key] = $value;
            }

            return $return;
        };

        if ($useGenerator) {
            $retriever = function ($object) use ($retriever) {
                $object = call_user_func($retriever, $object);
                foreach ($object as $key => $value) {
                    yield $key           => $value;
                }
            };
        }

        $this
            ->CoreTrait_getCoreObject()
            ->setRetriever($retriever)
        ;

        return $this;
    }
}
