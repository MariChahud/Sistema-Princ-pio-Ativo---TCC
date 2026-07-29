<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Princípio Ativo</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
  <div class="login-page">
    <div class="login-card">
      <div class="login-header">
        <a href="{{ route('home') }}">
          <img src="{{ asset('images/logo.png') }}" alt="Princípio Ativo">
        </a>
        <h1>Bem-vindo de volta</h1>
        <p>Faça login para acessar o sistema</p>
      </div>

      {{-- Tratamento visual de erros de validação vindo do controlador --}}
      @if ($errors->any())
        <div class="error-message" style="display:flex;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="seu@email.com" required autofocus>
        </div>

        <div class="form-group">
          <label for="password">Senha</label>
          <div class="input-with-icon">
            <input type="password" id="password" name="password" placeholder="••••••••" required>
          </div>
        </div>

        

        <button type="submit" class="btn btn-primary" style="width: 100%;">Entrar</button>
      </form>

      <div class="login-footer">
        <a href="{{ route('home') }}">← Voltar para o início</a>
      </div>
    </div>
  </div>

 

  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>