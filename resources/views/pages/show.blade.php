@extends('layouts.app')

@section('content')
    <main>
        <section class="hero">
            <div class="wrap">
                <h1>{{ $page?->title ?? 'АО «ТомскАгроИнвест»' }}</h1>
                <p>
                    Современная версия сайта на Laravel. Следующий этап — импорт страниц,
                    меню, блоков, медиа и редиректов из старой самописной CMS.
                </p>
            </div>
        </section>

        <section class="content">
            <div class="wrap">
                <article class="card">
                    @if ($page?->content)
                        {!! $page->content !!}
                    @else
                        <h2>Каркас нового сайта создан</h2>
                        <p>
                            Этот экран подтверждает, что новая публичная часть готова к подключению
                            реального контента из дампа <code>u2818473_agroinvest.sql</code>.
                        </p>
                        <div class="grid">
                            <div class="pill">Страницы и SEO</div>
                            <div class="pill">Меню и блоки</div>
                            <div class="pill">Медиа и галереи</div>
                            <div class="pill">Формы и заявки</div>
                        </div>
                    @endif
                </article>
            </div>
        </section>
    </main>
@endsection
