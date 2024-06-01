<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu Sistema SaaS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body>

    <!-- Navigation Menu -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
                <img src="assets/img/logo.png" alt="Logo" height="80">
            </a>
            <!-- Toggle Button for Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Menu Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Recursos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Planos e Preços</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center py-5" style="background-image: url('assets/img/banner.png'); background-size: cover;
    background-position: center;">
    <div class="container">
        <br><br><br>
        <h1 class="display-2 mb-4">Seu Sistema SaaS</h1>
        <p class="lead mb-5">Uma solução completa para gestão de empresas. Simplifique sua rotina e aumente sua eficiência.</p>
        <br><br><br><br><br><br>
        <a href="#features" class="btn btn-primary btn-lg">Saiba Mais</a>
    </div>
</section>

    <!-- Features Section -->
    <section id="features" class="features py-5">
        <div class="container">
            <h2 class="display-4 mb-5 text-center">Recursos Principais</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature">
                        <h3 class="mb-3">Gestão de Funcionários</h3>
                        <p>Gerencie facilmente os dados de seus funcionários, incluindo cadastro, consulta e relatórios.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature">
                        <h3 class="mb-3">Controle de Ponto</h3>
                        <p>Registre e gerencie os pontos dos seus funcionários de forma eficiente, com opção de registro
                            manual ou automático.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature">
                        <h3 class="mb-3">Relatórios Personalizados</h3>
                        <p>Crie relatórios detalhados e personalizados para analisar o desempenho da sua equipe e da sua
                            empresa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <!-- Pricing Section -->
    <section class="pricing py-5" id="pricing">
        <div class="container">
            <h2 class="display-4 mb-5 text-center">Planos e Preços</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="plan">
                        <h3 class="mb-3">Básico</h3>
                        <p class="lead mb-4">$99/mês</p>
                        <ul class="list-unstyled">
                            <li>Acesso ao sistema web da empresa</li>
                            <li>Controle do ponto pela empresa</li>
                            <br>
                        </ul>
                        <a href="#" class="btn btn-primary">Contratar</a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="plan">
                        <h3 class="mb-3">Padrão</h3>
                        <p class="lead mb-4">$199/mês</p>
                        <ul class="list-unstyled">
                            <li>Acesso ao sistema web da empresa</li>
                            <li>Registro de ponto online para funcionários</li>
                            <br>
                        </ul>
                        <a href="#" class="btn btn-primary">Contratar</a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="plan">
                        <h3 class="mb-3">Premium</h3>
                        <p class="lead mb-4">$299/mês</p>
                        <ul class="list-unstyled">
                            <li>Acesso ao sistema web da empresa</li>
                            <li>Registro de ponto online para funcionários</li>
                            <li>App exclusivo para empresa e funcionários</li>
                        </ul>
                        <a href="#" class="btn btn-primary">Contratar</a>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="">
            <h2 class="display-4 mb-4 text-center">Entre em Contato</h2>
            <p class="lead mb-4 text-center">Ficou com alguma dúvida ou precisa de mais informações? Entre em contato
                conosco!</p>
            <div class="container mx-auto">
                <form>
                    <!-- Linha 1: Nome e Email -->
                    <div class="row">
                        <div class="col-md-6 mb-3 ">
                            <input type="text" class="form-control" placeholder="Seu Nome" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="email" class="form-control" placeholder="Seu Email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <textarea class="form-control" placeholder="Sua Mensagem" rows="5" required></textarea>
                        </div>
                    </div>

                    <!-- Botão de Envio -->
                    <button type="submit" class="btn btn-primary btn-lg">Enviar Mensagem</button>
                </form>
            </div>
        </div>
    </section>
    <footer class="footer py-4">
        <div class="container text-center">
            <p>&copy; 2024 Seu Sistema SaaS. Todos os direitos reservados.</p>
            <p><a href="master/login.php">Login Administrativo</a></p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>