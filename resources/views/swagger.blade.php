<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng Ký Tích Xanh VN - Swagger API Docs</title>
    
    <!-- Swagger UI Assets from unpkg CDN -->
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css" />
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚡</text></svg>">
    
    <style>
        html {
            box-sizing: border-box;
            overflow: -y-scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            background: #0f172a; /* Dark sleek slate theme to match our app */
        }
        /* Custom styled header to align with our brand identity */
        .brand-header {
            background: #030712;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #1e293b;
        }
        .brand-logo {
            color: #ffffff;
            font-size: 20px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: ui-sans-serif, system-ui, sans-serif;
        }
        .brand-logo span {
            color: #2563eb;
        }
        .badge {
            background: rgba(37, 99, 235, 0.15);
            border: 1px solid rgba(37, 99, 235, 0.3);
            color: #60a5fa;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 9999px;
            font-weight: 500;
        }
        /* Dark mode customization for Swagger UI */
        .swagger-ui {
            background-color: #0f172a !important;
            filter: invert(0.9) hue-rotate(180deg); /* Sleek automated dark-mode styling */
        }
        .swagger-ui .info .title {
            color: #f8fafc !important;
        }
        .swagger-ui .scheme-container {
            background-color: #1e293b !important;
            box-shadow: none !important;
            border-bottom: 1px solid #334155 !important;
        }
    </style>
</head>
<body>
    <div class="brand-header">
        <a href="/" class="brand-logo">
            ⚡ Đăng Ký Tích Xanh<span>.vn</span>
        </a>
        <div style="margin-left: auto; display: flex; align-items: center; gap: 10px;">
            <span class="badge">API v1.0.0</span>
            <span class="badge">OpenAPI 3.0</span>
        </div>
    </div>

    <!-- Container where Swagger UI will render -->
    <div id="swagger-ui"></div>

    <!-- Swagger Bundle scripts -->
    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js" charset="UTF-8"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-standalone-preset.js" charset="UTF-8"></script>
    
    <script>
        window.onload = function() {
            // Load and mount Swagger UI dynamically pointing to local swagger.json
            const ui = SwaggerUIBundle({
                url: "/swagger.json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "BaseLayout"
            });
            window.ui = ui;
        };
    </script>
</body>
</html>
