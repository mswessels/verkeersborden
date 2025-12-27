<?php

namespace Database\Seeders;

use App\Sign;
use App\Services\SignTextGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SignTextSeeder extends Seeder
{
    public function run()
    {
        if (!Schema::hasTable('signs')) {
            return;
        }

        $generator = new SignTextGenerator();

        Sign::chunk(100, function ($signs) use ($generator) {
            foreach ($signs as $sign) {
                $meaning = $this->valueOrFallback($sign->meaning ?? null, $generator->buildMeaning($sign));
                $mnemonic = $this->valueOrFallback($sign->mnemonic ?? null, $generator->buildMnemonic($sign));
                $mistake = $this->valueOrFallback($sign->mistake ?? null, $generator->buildMistake($sign));

                $questions = is_array($sign->practice_questions) && count($sign->practice_questions) > 0
                    ? $sign->practice_questions
                    : $generator->buildQuestions($sign);

                $sign->meaning = $meaning;
                $sign->mnemonic = $mnemonic;
                $sign->mistake = $mistake;
                $sign->practice_questions = $questions;
                $sign->save();
            }
        });
    }

    private function valueOrFallback(?string $value, string $fallback): string
    {
        $value = $value ? trim($value) : '';

        return $value !== '' ? $value : $fallback;
    }
}
