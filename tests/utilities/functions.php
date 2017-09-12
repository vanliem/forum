<?php 

function make($className, $attributes = [])
{
	return factory($className)->make($attributes);
}


function create($className, $attributes = [])
{
	return factory($className)->create($attributes);
}
