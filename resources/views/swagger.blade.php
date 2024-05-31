<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('swagger-ui/swagger-ui.css') }}">
    <script src="{{ asset('swagger-ui/swagger-ui-bundle.js') }}"></script>
    <script src="{{ asset('swagger-ui/swagger-ui-standalone-preset.js') }}"></script>
</head>
<body>
<div id="swagger-ui"></div>
<script>
    window.onload = function() {
        const ui = SwaggerUIBundle({
            url: "{{ url('/api/api-docs.json') }}",
            dom_id: '#swagger-ui',
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            layout: "BaseLayout",
            deepLinking: true,
            showExtensions: true,
            showCommonExtensions: true
        });
        window.ui = ui;
    };
</script>
</body>
</html>
