<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('titulo', 'Painel') - Princípio Ativo</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  @stack('estilos')
</head>
<body>
  <div class="dashboard">
    @include('partials.sidebar')
    
    <main class="main-content">
      <div class="top-bar">
        <button class="mobile-sidebar-btn" data-toggle-sidebar>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <h1>@yield('titulo', 'Painel')</h1>
        <div></div>
      </div>

      <div class="page-content">
        {{-- Mensagens de feedback globais do sistema --}}
        @if (session('sucesso'))
          <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('sucesso') }}</div>
        @endif

        @if (session('erro'))
          <div class="alert alert-danger" style="margin-bottom:1rem;">{{ session('erro') }}</div>
        @endif

        

        {{-- Aqui entra o conteúdo dinâmico de cada página --}}
        @yield('conteudo')
      </div>
    </main>
  </div>

  @yield('modais')

  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>