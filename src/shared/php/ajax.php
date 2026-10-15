<?php

class AJAX
{
    public static function call(string $url, string $elementId = 'page-content'): string
    {
        $generatedId = uniqid();
        $id = "{$elementId}_{$generatedId}";

        $jsUrl = json_encode($url);
        $jsId = json_encode($id);
        $jsScriptId = "script_{$generatedId}";
        $jsScriptIdJson = json_encode($jsScriptId);

        return <<<HTML
        <div class="page" id="$id">
            <div class="spinner-container">
                <div class="spinner"></div>
            </div>
        </div>

        <script id="$jsScriptId">
            loadContent($jsUrl, $jsId);
            const script = document.getElementById($jsScriptIdJson);
            script?.remove();
        </script>
        HTML;
    }
}
