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
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $validator = $this->buildValidator();

        $valid = $validator->validate($this);

        foreach($validator->validate($this) as $exception){
            $property = $exception->getPropertyPath();
            throw new \Exception("Provided $property is invalid: ".$exception->getMessage());
        }
        parent::save();
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
}
