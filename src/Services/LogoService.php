<?php

namespace Services;

class LogoService
{
    private const LOGO_MAP = [
        'netflix' => 'netflix.com',
        'spotify' => 'spotify.com',
        'youtube' => 'youtube.com',
        'adobe' => 'adobe.com',
        'amazon' => 'amazon.com',
        'prime' => 'primevideo.com',
        'disney' => 'disneyplus.com',
        'apple' => 'apple.com',
        'hbo' => 'hbomax.com',
        'max' => 'max.com',
        'chatgpt' => 'openai.com',
        'github' => 'github.com',
        'playstation' => 'playstation.com',
        'xbox' => 'xbox.com',
        'nintendo' => 'nintendo.com',
        'discord' => 'discord.com',
        'slack' => 'slack.com',
        'figma' => 'figma.com',
        'canva' => 'canva.com',
        'dropbox' => 'dropbox.com',
        'notion' => 'notion.so'
    ];

    public static function getLogoUrl(string $name): ?string
    {
        $normalized = strtolower(trim($name));

        // Dokładne dopasowanie
        if (isset(self::LOGO_MAP[$normalized])) {
            $domain = self::LOGO_MAP[$normalized];
            return "https://www.google.com/s2/favicons?domain={$domain}&sz=128";
        }

        // Częściowe dopasowanie (np. "Spotify Premium" -> "spotify.com")
        foreach (self::LOGO_MAP as $key => $domain) {
            if (str_contains($normalized, $key)) {
                return "https://www.google.com/s2/favicons?domain={$domain}&sz=128";
            }
        }

        return null;
    }
}