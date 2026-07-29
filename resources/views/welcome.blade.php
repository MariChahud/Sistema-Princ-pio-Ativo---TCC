<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Princípio Ativo - Farmácia de Manipulação</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

  <header class="header">
    <div class="container header-content">
      <a href="{{ route('home') }}" class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Princípio Ativo" class="logo-img">
        <div class="logo-text">
          <span class="logo-title">Princípio Ativo</span>
          <span class="logo-subtitle">Sistema de Manipulação Farmacêutica</span>
        </div>
      </a>
      <nav class="nav">
        <a href="#sobre">Sobre</a>
        <a href="#servicos">Serviços</a>
        <a href="#contato">Contato</a>
      </nav>
      <a href="{{ route('login') }}" class="btn btn-primary">Entrar</a>
      <button class="mobile-menu-btn" data-toggle-mobile-menu>
        <span></span><span></span><span></span>
      </button>
    </div>
    
    <nav class="mobile-nav" id="mobileNav">
      <a href="#sobre">Sobre</a>
      <a href="#servicos">Serviços</a>
      <a href="#contato">Contato</a>
      <a href="{{ route('login') }}" class="btn btn-primary">Entrar</a>
    </nav>
  </header>

  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <img src="{{ asset('images/logo.png') }}" alt="Princípio Ativo" class="hero-logo">
        <h1>Sistema de Gestão Farmacêutica</h1>
        <p>Precisão e segurança em cada etapa do processo de manipulação</p>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Acessar Sistema</a>
      </div>
      
      <div class="hero-features">
        <div class="feature-card">
          <div class="feature-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <h3>Gestão de Usuários</h3>
          <p>Controle de acesso por perfil</p>
        </div>
        
        <div class="feature-card">
          <div class="feature-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>          </div>
          <h3>Controle de Estoque</h3>
          <p>Lotes e validades automatizados</p>
        </div>
        
        <div class="feature-card">
          <div class="feature-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
          </div>
          <h3>Receitas Digitais</h3>
          <p>Cadastro e acompanhamento</p>
        </div>
      </div>
    </div>
  </section>

   <section class="about" id="sobre">
    <div class="container">
      <h2>Quem Somos</h2>
      <div class="about-content">
        <div class="about-text">
          <p>O Sistema Princípio Ativo foi desenvolvido especialmente para farmácias de manipulação, oferecendo uma solução completa para gestão de processos.</p>
          <p>Nossa missão é garantir precisão e segurança em cada etapa, desde o cadastro de receitas até o controle financeiro.</p>
          <ul class="about-list">
            <li>Controle preciso de matérias-primas e lotes</li>
            <li>Rastreabilidade completa de receitas</li>
            <li>Gestão financeira integrada</li>
          </ul>
        </div>
        <div class="about-image">
          <img src="{{ asset('images/logo.png') }}" alt="Princípio Ativo">
        </div>
      </div>
    </div>
  </section>

   <section class="services" id="servicos">
    <div class="container">
      <h2>Módulos do Sistema</h2>
      <div class="services-grid">
        @foreach ([
          ['Usuários', 'Cadastro e controle de acesso de funcionários com diferentes perfis'],
          ['Clientes', 'Gestão completa de clientes com histórico de compras'],
          ['Produtos', 'Controle de estoque com lotes, validades e alertas automáticos'],
          ['Receitas', 'Cadastro de receitas com rastreamento do status de produção'],
          ['Financeiro', 'Controle de entradas, saídas e fluxo de caixa'],
        ] as [$titulo, $desc])
          <div class="service-card">
            <div class="service-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/></svg>
            </div>
            <h3>{{ $titulo }}</h3>
            <p>{{ $desc }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="contact" id="contato">
    <div class="container">
      <h2>Contato</h2>
      <div class="contact-content">
        <div class="contact-info">
          <div class="contact-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <span>(11) 99999-9999</span>
          </div>
          <div class="contact-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <span>contato@principioativo.com.br</span>
          </div>
          <div class="contact-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span>Rua das Farmácias, 123 - São Paulo, SP</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-logo">
          <img src="{{ asset('images/logo.png') }}" alt="Princípio Ativo">
          <p>Sistema de Gestão Farmacêutica</p>
        </div>
        <div class="footer-links">
          <h4>Links</h4>
          <a href="#sobre">Sobre</a>
          <a href="#servicos">Serviços</a>
          <a href="#contato">Contato</a>
          <a href="{{ route('login') }}">Entrar</a>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} Princípio Ativo - Farmácia de Manipulação. Todos os direitos reservados.</p>
      </div>
    </div>
  </footer>

  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>