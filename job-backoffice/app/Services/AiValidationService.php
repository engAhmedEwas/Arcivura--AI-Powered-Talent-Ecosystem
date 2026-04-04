<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiValidationService
{
    protected $apiKey;
    protected $url;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        // تم التحديث لـ gemini-1.5-flash (أكثر استقراراً من نسخة preview التي كنت تستخدمها)
        $this->url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent';
    }

    public function validateKeywordsBatch($categoryName, array $keywords)
    {
        // تم تحسين الـ Prompt ليكون صارماً جداً في تنسيق الرد
        $prompt = "Task: Validate if these keywords: [" . implode(', ', $keywords) . "] are related to the category: '{$categoryName}'.
                   Return ONLY a raw JSON array. No markdown, no explanations.
                   Expected Format: [{\"name\":\"keyword_name\", \"score\":number_0_to_100}]";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->retry(3, 1000) // إعادة المحاولة في حال حدوث ضغط على السيرفر
            ->post("{$this->url}?key={$this->apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.1, // لضمان دقة الرد وعدم "الهلوسة"
                    'response_mime_type' => 'application/json',
                ]
            ]);
            dd($response);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');
                
                // تنظيف النص من أي زوائد Markdown مثل ```json
                $cleanJson = trim(str_replace(['```json', '```'], '', $content));
                
                $decoded = json_decode($cleanJson, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error("Gemini JSON Parse Error: " . json_last_error_msg());
                    return [];
                }

                return is_array($decoded) ? $decoded : [];
            }

            Log::error("Gemini API Error: " . $response->body());
            return [];

        } catch (\Exception $e) {
            Log::error("AiValidationService Exception: " . $e->getMessage());
            return [];
        }
    }
}