<?php

function make($className, $attributes = [], $times = null)
{
    return factory($className, $times)->make($attributes);
}


function create($className, $attributes = [], $times = null)
{
    return factory($className, $times)->create($attributes);
}
