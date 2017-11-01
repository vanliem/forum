<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var Request
     */
    protected $request, $builder;
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * Filters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * @param $filter
     * @return bool
     */
    public function hasFilter($filter) :bool
    {
        return $this->request->has($filter);
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array_filter($this->request->only($this->filters));
    }
}