<?php

namespace App\Http\Api\Actions;

abstract class Action
{
    protected function validate(array $rules = [], array $messages = [], bool $stopOnFirstFailure = false): array
    {
        if (empty($rules) && method_exists($this, 'rules')) {
            $rules = app()->call([$this, 'rules']);
        }

        if (empty($messages) && method_exists($this, 'messages')) {
            $messages = app()->call([$this, 'messages']);
        }

        return validator(request()->all(), $rules, $messages)
            ->stopOnFirstFailure($stopOnFirstFailure)
            ->validate();
    }
}
