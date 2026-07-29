<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Google_Cloud-4285F4?style=for-the-badge&logo=google-cloud&logoColor=white" alt="Google Cloud">
  <img src="https://img.shields.io/badge/License-MIT-green.style=for-the-badge" alt="License">
</p>

<h1 align="center">📍 RemoteWorkplace — Descubra Espaços Perfeitos para Trabalho Remoto</h1>

<p align="center">
  <b>RemoteWorkplace</b> é uma plataforma moderna e intuitiva projetada para nômades digitais, freelancers e profissionais remotos encontrarem e avaliarem os melhores cafés, coworkings, bibliotecas e lobbies de hotéis para trabalhar com alta produtividade.
</p>

---

## ✨ Principais Funcionalidades

- ⚡ **Métricas Reais de Trabalho Remoto**: Visualize velocidade do Wi-Fi (Mbps), abundância de tomadas elétricas e nível de ruído do ambiente.
- ☀️ **Clima em Tempo Real**: Integração automática com APIs meteorológicas (**Open-Meteo** e **OpenWeatherMap**) exibindo a temperatura ao vivo no local através de coordenadas de latitude/longitude.
- 🔍 **Filtros e Busca Inteligente**: Pesquise spots por nome, localização, categoria (Cafés, Coworkings, Bibliotecas, Lobbies), nível de ruído ou tomadas disponíveis.
- 🔑 **Autenticação Flexível**: Login seguro nativo (E-mail/Senha com criptografia AES-256) e login social instantâneo via **Google OAuth 2.0 (Socialite)**.
- ❤️ **Favoritos & Avaliações**: Salve seus locais favoritos e compartilhe avaliações detalhadas com notas de Wi-Fi e conforto para a comunidade.
- 🎨 **Interface Premium (Glassmorphism)**: Design responsivo e futurista em Dark Mode, estilizado com CSS3 Vanilla puro, micro-animações e tipografia refinada.
- ☁️ **Sincronização Supabase & MySQL**: Arquitetura híbrida pronta para armazenar dados localmente e sincronizar registros na nuvem.

---

## 🛠️ Tecnologias Utilizadas

| Camada | Tecnologia |
| :--- | :--- |
| **Backend** | PHP 8.2+, Laravel 11.x |
| **Frontend** | Blade Components, Vanilla CSS3 (Glassmorphism), JavaScript (ES6+) |
| **Autenticação** | Laravel Socialite (Google OAuth 2.0), Custom Auth |
| **Banco de Dados** | MySQL 8.0 / MariaDB, Supabase (DB Cloud) |
| **APIs Externas** | Open-Meteo API, OpenWeatherMap API, Google Maps |

---

## 🚀 Como Executar o Projeto Localmente

### Pré-requisitos
- **PHP** >= 8.2
- **Composer**
- **Node.js** & **npm** (opcional para build de assets)
- Banco de dados **MySQL** ou **MariaDB**

### Passo a Passo de Instalação

1. **Clonar o repositório:**
   ```bash
   git clone https://github.com/peterBRjr/remote.git
   cd remote
   ```

2. **Instalar as dependências do PHP (Composer):**
   ```bash
   composer install
   ```

3. **Configurar as Variáveis de Ambiente:**
   Copie o arquivo de exemplo e crie o seu `.env`:
   ```bash
   cp .env.example .env
   ```

4. **Gerar a Chave da Aplicação:**
   ```bash
   php artisan key:generate
   ```

5. **Configurar o Banco de Dados no arquivo `.env`:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=remotespot
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Executar as Migrations e Seeds:**
   ```bash
   php artisan migrate --seed
   ```

7. **Iniciar o Servidor de Desenvolvimento:**
   ```bash
   php artisan serve
   ```
   Acesse a aplicação no navegador em: `http://127.0.0.1:8000`

---

## 🔑 Configuração do Google OAuth 2.0

Para habilitar o botão de **Login com o Google**:

1. Acesse o **[Google Cloud Console](https://console.cloud.google.com/)**.
2. Crie uma credencial do tipo **ID do cliente OAuth 2.0**.
3. Adicione nos **URIs de redirecionamento autorizados**:
   - Local: `http://127.0.0.1:8000/auth/google/callback`
   - Produção: `https://SEU-DOMINIO.com/auth/google/callback`
4. Adicione as chaves no seu arquivo `.env`:
   ```env
   GOOGLE_CLIENT_ID=seu_client_id_aqui
   GOOGLE_CLIENT_SECRET=seu_client_secret_aqui
   GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
   ```

---

## 🌐 Deploy na Hospedagem (InfinityFree)

O projeto inclui suporte completo para execução em ambientes de hospedagem compartilhada como a **InfinityFree**:

- **Suporte a Reverse Proxies**: O arquivo `bootstrap/app.php` já possui `trustProxies(at: '*')` ativado.
- **Forçar HTTPS em Produção**: O `AppServiceProvider.php` força esquemas `https://` em produção para evitar erros de `redirect_uri_mismatch` e sessão expirada (`419 Page Expired`).
- **Banco de Dados Dump**: O repositório contém o arquivo `database/infinityfree_dump.sql` para ser importado diretamente pelo phpMyAdmin da hospedagem.

---

## 📄 Licença

Este projeto é um software de código aberto licenciado sob a [MIT License](LICENSE).

---

<p align="center">
  Desenvolvido com 💜 para a comunidade de trabalhadores remotos.
</p>