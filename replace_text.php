<?php

function replaceInDir($dir) {
    if (!is_dir($dir)) return;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            replaceInDir($path);
        } else {
            if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                $content = file_get_contents($path);
                $original = $content;
                
                // Replacements
                $content = str_replace('Boiema Platform', 'I.M System', $content);
                $content = str_replace('Boiema', 'I.M System', $content);
                $content = str_replace('Platform for Economic Development', 'Plateforme Numérique des Services', $content);
                $content = str_replace('+212 XX XXX XXXX', '+212 5 37 77 12 34', $content);
                
                // If there are other hardcoded Boiema texts
                if ($content !== $original) {
                    file_put_contents($path, $content);
                    echo "Updated: $path\n";
                }
            }
        }
    }
}

replaceInDir(__DIR__ . '/resources/views');
replaceInDir(__DIR__ . '/resources/lang');

echo "Replacement complete.\n";
