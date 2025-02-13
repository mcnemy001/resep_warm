<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ImageToRecipeController extends Controller
{
    public function index()
    {
        return view('image-to-recipe.index');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $image = $request->file('image');
            $imagePath = $image->store('recipe-images', 'public');
            $imageUrl = Storage::url($imagePath);
            $imageContents = Storage::disk('public')->get($imagePath);
            $base64Image = base64_encode($imageContents);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-goog-api-key' => env('GEMINI_API_KEY')
            ])->post('https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent', [
                'contents' => [
                    'parts' => [
                        [
                            'text' => "Please analyze this food image and provide a detailed recipe. The response must include:
                        
                        1. Dish Name (This is mandatory! Always provide a name.)
                        2. Dish Type (e.g., Main Course, Appetizer, Dessert)
                        3. Cook Time
                        4. Serving Size
                        5. Ingredients (with measurements)
                        6. Step-by-step Cooking Instructions"
                        ],
                        [
                            'inline_data' => [
                                'mime_type' => $image->getMimeType(),
                                'data' => $base64Image
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'topK' => 32,
                    'topP' => 1,
                    'maxOutputTokens' => 2048,
                ]
            ]);

            Log::info('API Response:', ['response' => $response->json()]);

            if ($response->failed()) {
                throw new \Exception('Failed to get response from Gemini API: ' . $response->body());
            }

            $recipeData = $this->processGeminiResponse($response->json());
            $recipeData['recipe_image'] = $imageUrl;

            Log::info('Processed Recipe Data:', ['recipeData' => $recipeData]);

            Session::put('recipeData', $recipeData);
            return redirect()->route('image-to-recipe.result');

        } catch (\Exception $e) {
            Log::error('Error in analyze method:', ['error' => $e->getMessage()]);
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            return back()->with('error', 'Error analyzing image: ' . $e->getMessage());
        }
    }

    public function showResult()
    {
        $recipeData = Session::get('recipeData');
        Log::info('Show Result Recipe Data:', ['recipeData' => $recipeData]);

        if (!$recipeData) {
            return redirect()->route('image-to-recipe.index')
                ->with('error', 'No analyzed data found.');
        }

        return view('image-to-recipe.result', compact('recipeData'));
    }

    private function processGeminiResponse($response)
    {
        Log::info('Processing Gemini Response:', ['response' => $response]);
        
        $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';
        Log::info('Extracted Text:', ['text' => $text]);
        
        $recipeData = [
            'food_name' => '',
            'dish_type' => '',
            'cook_time' => '',
            'serving_size' => '',
            'ingredients' => [],
            'instructions' => []
        ];

        // First try to find dish name directly
        if (preg_match('/1\.\s*\*{0,2}Dish Name:?\*{0,2}\s*([^\n]+)/i', $text, $matches)) {
            $recipeData['food_name'] = trim(preg_replace('/\*+/', '', $matches[1]));
        }

        $sections = explode("\n", $text);
        $currentSection = '';
        
        foreach ($sections as $line) {
            $line = trim(preg_replace('/\*+/', '', $line));
            if (empty($line)) continue;
            
            // Section detection
            if (stripos($line, 'dish name:') !== false || stripos($line, 'name:') !== false) {
                $currentSection = 'name';
                $parts = explode(':', $line, 2);
                if (isset($parts[1]) && empty($recipeData['food_name'])) {
                    $recipeData['food_name'] = trim($parts[1]);
                }
                continue;
            }
            if (stripos($line, 'dish type:') !== false) {
                $currentSection = 'type';
                $parts = explode(':', $line, 2);
                if (isset($parts[1])) {
                    $recipeData['dish_type'] = trim($parts[1]);
                }
                continue;
            }
            if (stripos($line, 'cook time:') !== false) {
                $currentSection = 'time';
                $parts = explode(':', $line, 2);
                if (isset($parts[1])) {
                    $recipeData['cook_time'] = trim($parts[1]);
                }
                continue;
            }
            if (stripos($line, 'serving size:') !== false || stripos($line, 'serves:') !== false) {
                $currentSection = 'serving';
                $parts = explode(':', $line, 2);
                if (isset($parts[1])) {
                    $recipeData['serving_size'] = trim($parts[1]);
                }
                continue;
            }
            if (stripos($line, 'ingredients:') !== false) {
                $currentSection = 'ingredients';
                continue;
            }
            if (stripos($line, 'instructions:') !== false || stripos($line, 'steps:') !== false) {
                $currentSection = 'instructions';
                continue;
            }

            // Process content based on current section
            switch ($currentSection) {
                case 'name':
                    if (empty($recipeData['food_name'])) {
                        $recipeData['food_name'] = $line;
                    }
                    break;
                case 'type':
                    if (empty($recipeData['dish_type'])) {
                        $recipeData['dish_type'] = $line;
                    }
                    break;
                case 'time':
                    if (empty($recipeData['cook_time'])) {
                        $recipeData['cook_time'] = $line;
                    }
                    break;
                case 'serving':
                    if (empty($recipeData['serving_size'])) {
                        $recipeData['serving_size'] = $line;
                    }
                    break;
                case 'ingredients':
                    if (!empty($line) && $line !== 'Ingredients:' && !str_starts_with($line, '*')) {
                        $ingredient = trim(preg_replace('/^[\d\-\*\.\s]+/', '', $line));
                        if (!empty($ingredient)) {
                            $recipeData['ingredients'][] = $ingredient;
                        }
                    }
                    break;
                case 'instructions':
                    if (!empty($line) && $line !== 'Instructions:' && !str_starts_with($line, '*')) {
                        $instruction = trim(preg_replace('/^[\d\-\*\.\s]+/', '', $line));
                        if (!empty($instruction)) {
                            $recipeData['instructions'][] = $instruction;
                        }
                    }
                    break;
            }
        }

        // Ensure food_name is not empty
        if (empty($recipeData['food_name'])) {
            $recipeData['food_name'] = 'Untitled Recipe';
        }

        Log::info('Final Recipe Data:', ['recipeData' => $recipeData]);
        return $recipeData;
    }
}