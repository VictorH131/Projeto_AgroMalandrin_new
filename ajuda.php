<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dúvidas Frequentes - Agro Malandrin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #034F0A;
            color: white;
            text-align: center;
            padding: 1rem 0;
            position: relative;
        }

        h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .faq-section h2 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .faq-section .faq-item {
            margin-bottom: 1.5rem;
        }

        .faq-item h3 {
            font-size: 1.2rem;
            color: #034F0A;
            cursor: pointer;
            margin: 0;
        }

        .faq-item p {
            display: none;
            margin-top: 0.5rem;
            font-size: 1rem;
            color: #555;
        }

        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 1rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #555;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
        }
    </style>
</head>
<body>

    <header>
        <button class="back-button" onclick="window.location.href='index.php'">Voltar para o Início</button>
        <h1>Dúvidas Frequentes</h1>
        <p>Agro Malandrin - Loja de Serviços e Produtos Agrícolas</p>
    </header>

    <div class="container">
        <section class="faq-section">
            <h2>Perguntas Frequentes</h2>

            <div class="faq-item">
                <h3>1. O que é o Agro Malandrin?</h3>
                <p>O Agro Malandrin é uma loja especializada na prestação de serviços agrícolas e na venda de produtos para o setor rural.</p>
            </div>

            <div class="faq-item">
                <h3>2. Vocês vendem no site?</h3>
                <p>Não, no momento não realizamos vendas diretamente pelo site. Você pode entrar em contato conosco ou visitar nossa loja física.</p>
            </div>

            <div class="faq-item">
                <h3>3. Quais são seus produtos?</h3>
                <p>Vendemos uma variedade de produtos agrícolas, como sementes, fertilizantes, defensivos, ferramentas e equipamentos para o campo.</p>
            </div>

            <div class="faq-item">
                <h3>4. Quais serviços agrícolas vocês oferecem?</h3>
                <p>Nossos serviços incluem consultoria técnica, manutenção de equipamentos agrícolas e assistência no campo para produtores rurais.</p>
            </div>

            <div class="faq-item">
                <h3>5. Vocês fazem entrega de produtos?</h3>
                <p>Sim, oferecemos entrega de produtos para várias regiões. Entre em contato para saber se atendemos sua localidade.</p>
            </div>

            <div class="faq-item">
                <h3>6. Como posso solicitar um orçamento?</h3>
                <p>Você pode solicitar um orçamento através do nosso formulário de contato ou ligando diretamente para nossa loja.</p>
            </div>

            <div class="faq-item">
                <h3>7. Existe algum custo para agendar uma visita técnica?</h3>
                <p>Depende da região e do tipo de serviço solicitado. Entre em contato conosco para verificar as condições e os custos envolvidos.</p>
            </div>

            <div class="faq-item">
                <h3>8. Por que devo avaliar o Agro Malandrin?</h3>
                <p>Ao avaliar nosso serviço, você nos ajuda a melhorar continuamente. Seu feedback é essencial para garantirmos a melhor experiência possível para nossos clientes.</p>
            </div>

            <div class="faq-item">
                <h3>9. Qual é o horário de funcionamento da loja?</h3>
                <p>Nossa loja funciona de segunda a sexta, das 8h às 18h, e aos sábados das 8h às 12h.</p>
            </div>

            <div class="faq-item">
                <h3>10. Como posso entrar em contato com vocês?</h3>
                <p>Você pode nos contatar por telefone, e-mail ou visitando nossa loja física. Todas as informações de contato estão disponíveis na página "Contato".</p>
            </div>

        </section>
    </div>

    <footer>
        <p>&copy; 2024 - Agro Malandrin. Todos os direitos reservados.</p>
    </footer>

    <script>
        // Script para abrir e fechar as respostas das FAQ ao clicar
        document.querySelectorAll('.faq-item h3').forEach(function(question) {
            question.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                if (answer.style.display === "block") {
                    answer.style.display = "none";
                } else {
                    answer.style.display = "block";
                }
            });
        });
    </script>

</body>
</html>
