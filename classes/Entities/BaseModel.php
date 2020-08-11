<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use Avado\MoodleAbstractionLibrary\Database\Builder;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BaseModel
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class BaseModel extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var boolean
     */
    protected $observed = false;

    /**
     * BaseModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        self::setConnectionResolver(new ConnectionResolver(['default' => Manager::connection('default')]));
        
        $this->setUpObserver();
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table ?? Str::snake(Str::pluralStudly(class_basename($this)));
    }

    /**
     * Get the current connection name for the model.
     *
     * @return string|null
     */
    public function getConnectionName()
    {
        return 'default';
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * @return void
     */
    protected function setUpObserver(): void
    {
        if(!$this->observed && defined(static::class.'::OBSERVER')){
            $this->registerObserver(static::OBSERVER);
        }
    }

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->removeChildrenFromAttributes();

        $validator = $this->buildValidator();
        $valid = $validator->validate($this);

        foreach($validator->validate($this) as $exception){
            $property = $exception->getPropertyPath();
            throw new \Exception("Provided $property is invalid: ".$exception->getMessage());
        }

        $saved = parent::save();
        
        if(defined('static::CHILDREN') && is_array(static::CHILDREN)){
            $this->saveChildren(static::CHILDREN);
        }
        return $saved;
    }

    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        if (! $this->exists) {
            return false;
        }

        return $this->fill($attributes)->save($options);
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        $totallyGuarded = $this->totallyGuarded();

        foreach ($this->fillableFromArray($attributes) as $key => $value) {
            $key = $this->removeTableFromKey($key);
            
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
                $this->$key = $value;
            }
        }
        return parent::fill($attributes);
    }

    /**
     * Recursively save the child relationships for this model
     *
     * @param array $children
     * @param array $data
     * @return void
     */
    protected function saveChildren(array $children, array $data = null)
    {
        foreach ($children as $childRelationship => $childClass) {
            $this->$childRelationship()->saveMany(
                array_map(function($child) use ($childClass){
                    if($child['id']){
                        $childId = $child['id'];
                        unset($child['id']);
                        return $childClass::find($childId)->fill($child);
                    }
                    return new $childClass($child);
                }, $this->objectToArray($data ?? $this->$childRelationship))
            );

            if(defined('$childClass::CHILDREN') && is_array($childClass::CHILDREN)){
                $this->saveChildren($childClass::CHILDREN, $data->$childRelationship);
            }
        }
    }

    /**
     * Strip child items from the models attributes array as we don't need them
     *
     * @return void
     */
    protected function removeChildrenFromAttributes()
    {
        if(defined('static::CHILDREN') && is_array(static::CHILDREN)){
            foreach(static::CHILDREN as $childRelationship => $childClass){
                unset($this->attributes[$childRelationship]);
            }
        }
    }

    /**
     *
     * @return void
     */
    protected function buildValidator()
    {
        return new RecursiveValidator(
            new ExecutionContextFactory(new Translator('en', null, '/avado_moodledata/assertcache')),
            new LazyLoadingMetadataFactory(
                new AnnotationLoader(new AnnotationReader())
            ),
            new ConstraintValidatorFactory()
        );
    }

    /**
     * Register a single observer with the model.
     *
     * @param  object|string  $class
     * @return void
     *
     * @throws \RuntimeException
     */
    protected function registerObserver($class)
    {
        foreach ($this->getObservableEvents() as $event) {
            if (method_exists($class, $event) && !$this->isObserved($event)) {
                static::registerModelEvent($event, $class.'@'.$event);
            }
        }
    }

    /**
     * Checks to see if the current event is already registered.
     *
     * @param String $event
     * @return bool
     */
    protected function isObserved(String $event): bool
    {
        return static::$dispatcher->hasListeners("eloquent.{$event}: ".static::class);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->$key = $value;

        parent::__set($key, $value);
    }

    protected function objectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }
}
