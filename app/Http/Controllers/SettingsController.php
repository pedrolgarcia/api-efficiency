<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Language;
use App\Theme;
use App\Setting;

class SettingsController extends Controller
{
    public function getLanguages() {
        $languages = Language::all();
        return response()->json($languages, 200);
    }

    public function getThemes() {
        $themes = Theme::all();
        return response()->json($themes, 200);
    }

    public function getSettings() {
        $user = auth()->user();
        $settings = $user->setting;
        return response()->json($settings, 200);
    }

    public function saveSettings(Request $request) {
        $user = auth()->user();
        $settings = $user->setting;
        
        if ($settings) {
            $settings->update([
                'tips' => $request->dicas,
                'language_id' => $request->linguagem,
                'theme_id' => $request->tema,
                'user_id' => $user->id,
            ]);
            
            return response()->json(['success' => 'Configurações alteradas com sucesso'], 200);
        } else {
            Setting::create([
                'tips' => $request->dicas,
                'language_id' => $request->linguagem,
                'theme_id' => $request->tema,
                'user_id' => $user->id,
            ]);
            return response()->json(['success' => 'Configurações criadas com sucesso'], 200);
        }
    }
}
