<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Testing\Assert as PHPUnit;
use Illuminate\Testing\TestResponse;
use Illuminate\Validation\ValidationException;

class TestsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerAssertValidationErrorRuleMacro();
    }

    protected function registerAssertValidationErrorRuleMacro(): void
    {
        TestResponse::macro('assertValidationErrorRule', function (string $errorKey) {
            // @phpstan-ignore-next-line
            if ($this->exception === null) {
                PHPUnit::fail('The response didn\'t throw any exception.');
            }

            if (!$this->exception instanceof ValidationException) {
                PHPUnit::fail('The response didn\'t throw a ValidationException.');
            }

            $errors = $this->exception->validator->failed();
            $flatErrors = array_map('strtolower', array_keys(Arr::dot($errors)));

            PHPUnit::assertTrue(
                ! is_null(collect($flatErrors)->first(fn (string $key) => Str::startsWith($key, strtolower($errorKey)))),
                "Key [$errorKey] not found in errors: \n\n " . json_encode($flatErrors, JSON_PRETTY_PRINT) . "\n"
            );

            return $this;
        });
    }
}
