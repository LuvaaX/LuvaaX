<?php

namespace Luvaax\GeneratorBundle\File\Model\ClassGenerator;

/**
 * Contains PHP class's metadata
 */
class ClassModel
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string[]
     */
    protected $annotations = array();

    /**
     * @var string[]
     */
    protected $uses = array();

    /**
     * @var string[]
     */
    protected $interfaces = array();

    /**
     * @var string
     */
    protected $inheritance;

    /**
     * @var string[]
     */
    protected $traits = array();

    /**
     * @var PropertyModel[]
     */
    protected $properties = array();

    /**
     * @var MethodModel[]
     */
    protected $methods = array();

    /**
     * Get the value of Namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set the value of Namespace
     *
     * @param string $namespace
     *
     * @return self
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Get the value of Uses
     *
     * @return string[]
     */
    public function getUses()
    {
        return $this->uses;
    }

    /**
     * Set the value of Uses
     *
     * @param string[] $uses
     *
     * @return self
     */
    public function setUses(array $uses)
    {
        $this->uses = $uses;

        return $this;
    }

    /**
     * Get the value of Interfaces
     *
     * @return string[]
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * Set the value of Interfaces
     *
     * @param string[] $interfaces
     *
     * @return self
     */
    public function setInterfaces(array $interfaces)
    {
        $this->interfaces = $interfaces;

        return $this;
    }

    /**
     * Get the value of Inheritance
     *
     * @return string
     */
    public function getInheritance()
    {
        return $this->inheritance;
    }

    /**
     * Set the value of Inheritance
     *
     * @param string $inheritance
     *
     * @return self
     */
    public function setInheritance($inheritance)
    {
        $this->inheritance = $inheritance;

        return $this;
    }

    /**
     * Get the value of Traits
     *
     * @return string[]
     */
    public function getTraits()
    {
        return $this->traits;
    }

    /**
     * Set the value of Traits
     *
     * @param string[] $traits
     *
     * @return self
     */
    public function setTraits(array $traits)
    {
        $this->traits = $traits;

        return $this;
    }

    /**
     * Get the value of Properties
     *
     * @return PropertyModel[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Set the value of Properties
     *
     * @param PropertyModel[] $properties
     *
     * @return self
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * Get the value of Methods
     *
     * @return MethodModel[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Set the value of Methods
     *
     * @param MethodModel[] $methods
     *
     * @return self
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;

        return $this;
    }


    /**
     * Get the value of Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Add a property to the class
     *
     * @param PropertyModel $property  Property to add
     * @param boolean       $hasSetter does the property have a setter?
     * @param boolean       $hasGetter does the property have a getter?
     *
     * @return self
     */
    public function addProperty(PropertyModel $property, $hasSetter = false, $hasGetter = false)
    {
        $this->properties[] = $property;

        if ($hasSetter) {
            $setter = new MethodModel();
            $setter
                ->setName('set' . ucfirst($property->getName()))
                ->setArguments(['$' . $property->getName()])
                ->setContent(sprintf('        $this->%1$s = $%1$s;
                
        return $this;', $property->getName()))
            ;

            $this->addMethod($setter);
        }

        if ($hasGetter) {
            $getter = new MethodModel();
            $getter
                ->setName('get' . ucfirst($property->getName()))
                ->setContent(sprintf('      return $this->%s;', $property->getName()))
            ;

            $this->addMethod($getter);
        }

        return $this;
    }

    /**
     * Adds a new method to the class
     *
     * @param MethodModel $method Method to add
     * @return self
     */
    public function addMethod(MethodModel $method)
    {
        $this->methods[] = $method;
        return $this;
    }

    /**
     * Adds a new use to the class
     *
     * @param string $use
     * @return self
     */
    public function addUse($use)
    {
        $this->uses[] = $use;
        return $this;
    }

    /**
     * Adds a new interface to the class
     *
     * @param string $interface
     * @return self
     */
    public function addInterface($interface)
    {
        $this->interfaces[] = $interface;
        return $this;
    }

    /**
     * Adds a new trait to the class
     *
     * @param string $trait
     * @return self
     */
    public function addTrait($trait)
    {
        $this->traits[] = $trait;
        return $this;
    }

    /**
     * Get the value of Annotations
     *
     * @return string[]
     */
    public function getAnnotations()
    {
        return $this->annotations;
    }

    /**
     * Set the value of Annotations
     *
     * @param string[] $annotations
     *
     * @return self
     */
    public function setAnnotations(array $annotations)
    {
        $this->annotations = $annotations;

        return $this;
    }

    /**
     * Adds a new annotation to the class
     *
     * @param string $annotation
     * @return self
     */
    public function addAnnotation($annotation)
    {
        $this->annotations[] = $annotation;
        return $this;
    }
}
