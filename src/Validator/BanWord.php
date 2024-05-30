<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{
  /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
  public $message = 'The value "{{ value }}" is not valid.';
}
