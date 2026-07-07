<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Log;
use OpenAI;
use OpenAI\Exceptions\ErrorException;

class QuizGeneratorService
{
    protected function client(): OpenAI\Client
    {
        return OpenAI::factory()
            ->withApiKey(config('services.openrouter.key'))
            ->withBaseUri('https://openrouter.ai/api/v1')
            ->withHttpHeader('HTTP-Referer', config('app.url'))
            ->withHttpHeader('X-Title', config('app.name'))
            ->make();
    }

    public function generate(
        string $topic,
        int $count = 3,
        array $types = ['single', 'multiple', 'true_false'],
    ): array {
        $typesList = implode(', ', array_map(fn ($t) => match ($t) {
            'single' => 'single choice (one correct answer)',
            'multiple' => 'multiple choice (one or more correct answers)',
            'true_false' => 'true/false (exactly 2 options)',
            default => $t,
        }, $types));

        $prompt = <<<PROMPT
Generate {$count} quiz questions about "{$topic}" in JSON format.

Return a JSON object with a "questions" array.

Each question must have:
- question: string
- type: string (one of: "single", "multiple", "true_false")
- options: array of objects, each with:
  - text: string
  - correct: boolean

Rules:
- Allowed types: {$typesList}
- For true_false: provide exactly 2 options ("True" and "False"), one marked correct
- For single: provide 3-4 options, exactly one marked correct
- For multiple: provide 3-4 options, at least one marked correct
- The questions should be educational and relevant to the topic

Answer with valid JSON only, no markdown wrapping, no explanation.
PROMPT;

        try {
            $response = $this->client()->chat()->create([
                'model' => config('services.openrouter.model'),
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'response_format' => ['type' => 'json_object'],
            ]);

            $content = json_decode($response->choices[0]->message->content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Failed to parse AI response as JSON: '.json_last_error_msg());
            }

            return $content['questions'] ?? [];

        } catch (ErrorException $e) {
            Log::error('OpenRouter API error: '.$e->getMessage());
            throw new \RuntimeException('AI service error: '.$e->getMessage());
        }
    }
}
