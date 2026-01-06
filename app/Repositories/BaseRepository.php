<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * @extends Model
 */
abstract class BaseRepository implements Repository
{
    public Model $model;

    public function __construct()
    {
        if (property_exists($this, 'modelName')) {
            $this->model = new $this->modelName();
        }
    }

    public function get($key)
    {
        return $this->model->{$key};
    }

    public static function getInstance()
    {
        return new static();
    }

    public function setModel(Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function __call($method, $args)
    {
        if (isset($this->model)) {
            return $this->model->{$method}(...$args);
        }
    }
}
