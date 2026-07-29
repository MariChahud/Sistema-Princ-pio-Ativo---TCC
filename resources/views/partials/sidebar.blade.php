{{-- Sidebar — links exibidos conforme o perfil do usuário (User::PERMISSOES) --}}
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <a href="{{ route('dashboard') }}" class="logo">
      <img src="{{ asset('images/logo.png') }}" alt="Princípio Ativo" class="logo-img" style="height: 80px !important;">
      <div class="logo-text"></div>
    </a>
  </div>

  <nav class="sidebar-nav">
    @auth
      @if (auth()->user()->podeAcessar('dashboard'))
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          Dashboard
        </a>
      @endif

      @if (auth()->user()->podeAcessar('usuarios'))
        <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          Usuários
        </a>
      @endif

      @if (auth()->user()->podeAcessar('clientes'))
        <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          Clientes
        </a>
      @endif

      @if (auth()->user()->podeAcessar('produtos'))
        <a href="{{ route('produtos.index') }}" class="{{ request()->routeIs('produtos.*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
          Produtos
        </a>
      @endif

      @if (auth()->user()->podeAcessar('receitas'))
        <a href="{{ route('receitas.index') }}" class="{{ request()->routeIs('receitas.*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          Receitas
        </a>
      @endif

      @if (auth()->user()->podeAcessar('financeiro'))
        <a href="{{ route('financeiro.index') }}" class="{{ request()->routeIs('financeiro.*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          Financeiro
        </a>
      @endif

      @if (auth()->user()->podeAcessar('relatorios') && Route::has('relatorios.index'))
        <a href="{{ route('relatorios.index') }}" class="{{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          Relatórios
        </a>
      @endif
    @endauth
  </nav>

  <div class="sidebar-footer">
    @auth
      <div class="user-info">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->nome, 0, 1)) }}</div>
        <div class="user-details">
          <span class="user-name">{{ auth()->user()->nome }}</span>
          <span class="user-role">{{ auth()->user()->perfilLabel() }}</span>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline" style="width: 100%; color: white; border-color: rgba(255,255,255,0.2);">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Sair
        </button>
      </form>
    @endauth
  </div>
</aside>