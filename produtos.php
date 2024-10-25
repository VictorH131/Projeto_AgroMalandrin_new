<?php

include_once "includes/header.php";

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Agro Malandrin</title>
    <style>
        /* Configuração do produto */
        #produto {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        #produto .menu-container {
            position: relative;
            width: 200px;
            margin: 20px;
        }

        #produto .menu-button {
            background-color: #03740E;
            color: white;
            padding: 15px;
            margin-top: -5%;
            margin-left: -30vh;
            border: 3px solid #034F0A;
            border-radius: 10px;
            width: 120%;
            text-align: left;
            font-size: 20px;
            cursor: pointer;
        }

        #produto .menu-button:hover {
            background-color: #FCDCB7;
            color: black;
            border: 3px solid #F6A507;
        }

        #produto .dropdown-menu {
            display: none;
            background-color: white;
            border: 10px solid black;
            border-radius: 10px;
            position: absolute;
            margin-bottom: 60%;
            margin-left: -30vh;
            top: 39px;
            width: 120%;
            z-index: 1;
        }

        #produto .dropdown-menu a {
            display: block;
            padding: 10px;
            border-radius: 6px;
            text-decoration: none;
            color: black;
            border-bottom: 2px solid black;
            border-top: 2px solid black;
            border-left: 1.8px solid black;
            border-right: 1.8px solid black;
        }

        #produto .dropdown-menu a:hover {
            background-color: #FCDCB7;
            color: black;
        }

        #produto .menu-container:hover .dropdown-menu {
            display: block;
            margin-top: 8%;
            border: 2px solid transparent;
            border-top: 1px solid black;
            border-left: 3px solid black;
            border-right: 3px solid black;
            border-bottom: 1px solid black;
            border-radius: 10px;
            padding-top: 0px;
            padding-bottom: 0px;
        }

        #produto #imagens img {
            width: 200px;
            height: 250px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.575);
            border: 3px solid #34a853; /* Borda verde */
            border-radius: 10px; /* Borda arredondada */
        }

        .caption {
            margin-top: -42px;
            font-size: 15px;
            text-align: center;
            color: #000;
            padding: 8px;
            width: 77%;
        }

        .nome-container {
            background-color: #fff;
            border: 2px solid #34a853; /* Borda verde */
            border-radius: 10px;
            text-align: center;
            height: 50px; /* Altura do quadrado */
            display: flex;
            align-items: center; /* Centraliza verticalmente */
            justify-content: center; /* Centraliza horizontalmente */
            width: 200px; /* Largura do quadrado igual à largura da imagem */
            margin-top: -10px; /* Ajusta a posição do quadrado se necessário */
        }

        /* Estilo para garantir que a altura da imagem e do quadrado sejam iguais */
        .produto-container {
            display: flex;
            flex-direction: column;
            align-items: center; /* Centraliza horizontalmente */
        }

    </style>
</head>

<body id="produto">

    

    <!-- Área do Departamento -->
    <div class="container my-4">
        <div class="menu-container mb-3">
            <button class="menu-button btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                ☰ Departamentos
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="alterarImagens1()">Rações</a></li>
                <li><a class="dropdown-item" href="#" onclick="alterarImagens2()">Ferramentas</a></li>
                <li><a class="dropdown-item" href="#" onclick="alterarImagens3()">Vestimentas</a></li>
                <li><a class="dropdown-item" href="#" onclick="alterarImagens4()">Remédios</a></li>
            </ul>
        </div>

        <!-- Grid com 4 colunas e 2 linhas -->
        <div class="row" id="imagens">
            <!-- Linha 1 -->
            <div class="col-md-3 col-sm-6 mb-4 produto-container">
                <img id="img1" src="multimidia/produtos/racao-vaca.png" class="img-fluid rounded" alt="Produto 1">
                <div class="nome-container">
                    <p id="nome1" class="mb-0"><strong>Ração para Vacas</strong></p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4 produto-container">
                <img id="img2" src="multimidia/produtos/master-LP.png" class="img-fluid rounded" alt="Produto 2">
                <div class="nome-container">
                    <p id="nome2" class="mb-0"><strong>Remédio Master LP</strong></p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4 produto-container">
                <img id="img3" src="multimidia/produtos/bone-vestimento.png" class="img-fluid rounded" alt="Produto 3">
                <div class="nome-container">
                    <p id="nome3" class="mb-0"><strong>Bone BKE NATIVE INDIAN</strong></p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4 produto-container">
                <img id="img4" src="multimidia/produtos/caixa-de-ferramenta.png" class="img-fluid rounded" alt="Produto 4">
                <div class="nome-container">
                    <p id="nome4" class="mb-0"><strong>Caixa de Ferramenta</strong></p>
                </div>
            </div>
            <!-- Linha 2 -->
            <div class="col-md-3 col-sm-6 mb-4 produto-container">
                <img id="img5" src="multimidia/produtos/escova-de-aco-circular.png" class="img-fluid rounded" alt="Produto 5">
                <div class="nome-container">
                    <p id="nome5" class="mb-0"><strong>Escova de Aço Circular</strong></p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4 produto-container">
                <img id="img6" src="multimidia/produtos/racao-porco.png" class="img-fluid rounded" alt="Produto 6">
                <div class="nome-container">
                    <p id="nome6" class="mb-0"><strong>Ração para Porcos</strong></p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4 produto-container">
                <img id="img7" src="multimidia/produtos/fipronil-gado-de-corte.png" class="img-fluid rounded" alt="Produto 7">
                <div class="nome-container">
                    <p id="nome7" class="mb-0"><strong>Fipronil de Gado de Corte</strong></p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4 produto-container">
                <img id="img8" src="multimidia/produtos/sandalia-vestimento.png" class="img-fluid rounded" alt="Produto 8">
                <div class="nome-container">
                    <p id="nome8" class="mb-0"><strong>Sandalia</strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-8" id="link_pag">
        <div class="menu-container mb-3">

            <nav aria-label="Navegação de página exemplo">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">Anterior</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Próximo</a></li>
                </ul>
            </nav>
        </div>
    </div>



    <script>
        // As funções de alterar imagens ainda podem ser usadas, mas não são mais necessárias ao carregar a página.
        function alterarImagens1() {
            const novasImagens = [
                "multimidia/produtos/racao-vaca.png",
                "multimidia/produtos/racao-aves-postura.png",
                "multimidia/produtos/racao-boi.png",
                "multimidia/produtos/racao-cachorro.png",
                "multimidia/produtos/racao-cavalo.png",
                "multimidia/produtos/racao-porco.png",
                "multimidia/produtos/error.png",
                "multimidia/produtos/error.png"
            ];

            const novasDescricoes = [
                "Ração para Vacas",
                "Ração para Aves de Postura",
                "Ração para Bois",
                "Ração para Cachorros",
                "Ração para Cavalos",
                "Ração para Porcos",
                "Item Faltante",
                "Item Faltante"
            ];

            for (let i = 1; i <= 8; i++) {
                document.getElementById('img' + i).src = novasImagens[i - 1];
                document.getElementById('nome' + i).innerHTML = '<strong>' + novasDescricoes[i - 1] + '</strong>';
            }
        }

        function alterarImagens2() {
            const novasImagens = [
                "multimidia/produtos/escova-de-aco-circular.png",                
                "multimidia/produtos/esmerilhadeira-angular.png",                
                "multimidia/produtos/parafusadeira-makita.png",                
                "multimidia/produtos/caixa-de-ferramenta.png",                
                "multimidia/produtos/serra_tico-tico.png",
                "multimidia/produtos/tupia-M3700B.png",
                "multimidia/produtos/error.png",
                "multimidia/produtos/error.png",
            ];

            const novasDescricoes = [
                "Escova de aço de Circular",
                "Esmerilhadeira Angular",
                "Parafusadeira Makita",
                "Caixa de Ferramenta",
                "Serra Tico-Tico",
                "Lepecid spray",
                "Tupia Makita",
                "Item Faltante",
                "Item Faltante"
            ];

            for (let i = 1; i <= 8; i++) {
                document.getElementById('img' + i).src = novasImagens[i - 1];
                document.getElementById('nome' + i).innerHTML = '<strong>' + novasDescricoes[i - 1] + '</strong>';
            }
        }

        function alterarImagens3() {
            const novasImagens = [
                "multimidia/produtos/sandalia-vestimento.png",                
                "multimidia/produtos/porta-celular-vestimento.png",              
                "multimidia/produtos/bota-masc-vestimento.png",                    
                "multimidia/produtos/bone-vestimento.png",                                  
                "multimidia/produtos/botina-vestimento.png",  
                "multimidia/produtos/sapatilha-vestimento.png",
                "multimidia/produtos/error.png",
                "multimidia/produtos/error.png",
            ];

            const novasDescricoes = [
                "Sandalia",
                "Porta Celular de Couro",
                "Bota Masculina",
                "Bone",
                "Botina",
                "Sapatilha",
                "Item Faltante",
                "Item Faltante"
            ];

            for (let i = 1; i <= 8; i++) {
                document.getElementById('img' + i).src = novasImagens[i - 1];
                document.getElementById('nome' + i).innerHTML = '<strong>' + novasDescricoes[i - 1] + '</strong>';
            }
        }

        function alterarImagens4() {
            const novasImagens = [
            "multimidia/produtos/fipronil-gado-de-corte.png",                
            "multimidia/produtos/mo-performance.png",              
            "multimidia/produtos/nex-gard-caes.png",                    
            "multimidia/produtos/master-LP.png",                                  
            "multimidia/produtos/treo-ace.png",  
            "multimidia/produtos/lepecid-spray.png",
            "multimidia/produtos/error.png",
            "multimidia/produtos/error.png",
            ];

            const novasDescricoes = [
                "Fipronil de Gado de Corte",
                "Mo Performance",
                "Nex Gard Caes e Gatos",
                "Master LP",
                "Treo",
                "Lepecid Spray",
                "Item Faltante",
                "Item Faltante"
            ];

            for (let i = 1; i <= 8; i++) {
                document.getElementById('img' + i).src = novasImagens[i - 1];
                document.getElementById('nome' + i).innerHTML = '<strong>' + novasDescricoes[i - 1] + '</strong>';
            }
        }
    </script>

</body>

</html>
