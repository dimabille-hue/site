<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'АО «ТомскАгроИнвест»' }}</title>
    <meta name="description" content="{{ $page?->meta_description ?? 'Современная версия сайта АО «ТомскАгроИнвест».' }}">
    <style>
        :root { color-scheme: light; font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        body { margin: 0; background: #f7f4ef; color: #202015; }
        .page { min-height: 100vh; display: flex; flex-direction: column; }
        .header, .footer { background: #1f3b25; color: #fff; }
        .wrap { width: min(1120px, calc(100% - 32px)); margin: 0 auto; }
        .header-inner { display: flex; justify-content: space-between; align-items: center; padding: 22px 0; gap: 24px; }
        .brand { font-weight: 800; letter-spacing: .02em; }
        .nav { display: flex; gap: 18px; flex-wrap: wrap; }
        .nav a { color: #fff; text-decoration: none; opacity: .88; }
        .nav a:hover { opacity: 1; }
        .hero { padding: 84px 0 72px; background: linear-gradient(135deg, #e7dcc9, #f8f3ea); }
        .hero h1 { margin: 0 0 20px; font-size: clamp(36px, 7vw, 72px); line-height: .95; color: #24351f; }
        .hero p { max-width: 720px; font-size: 20px; line-height: 1.6; color: #4d493c; }
        .content { flex: 1; padding: 56px 0; }
        .card { background: #fff; border-radius: 24px; padding: 32px; box-shadow: 0 18px 50px rgba(42, 33, 17, .08); }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-top: 24px; }
        .pill { background: #eef3e9; border-radius: 18px; padding: 18px; color: #2d4326; }
        .footer { padding: 28px 0; margin-top: auto; }
    </style>
</head>
<body>
<div class="page">
    <header class="header">
        <div class="wrap header-inner">
            <div class="brand">АО «ТомскАгроИнвест»</div>
            <nav class="nav" aria-label="Главное меню">
                <a href="/">Главная</a>
                <a href="/istoriya-kompanii">О компании</a>
                <a href="/kommercheskaya-deyatel-nost">Деятельность</a>
                <a href="/kontakty">Контакты</a>
            </nav>
        </div>
    </header>

    @yield('content')

    <footer class="footer">
        <div class="wrap">Новая версия сайта готовится на Laravel. Данные будут перенесены из legacy CMS.</div>
    </footer>
</div>
</body>
</html>
