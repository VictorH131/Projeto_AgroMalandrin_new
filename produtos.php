<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Agro Malandrin</title>
   
</head>
 
<?php
include_once 'includes/header.php'
?>
 
<body id="produto">
   
    <!-- Area do Departamento -->
 
    <div class="menu-container">
        <button class="menu-button"><strong>☰ Departamentos</strong></button>
        <div class="dropdown-menu">
            <a href="#" onclick="alterarImagens1()">Rações </a>
            <a href="#" onclick="alterarImagens2()">Ferramentas </a>
            <a href="#" onclick="alterarImagens3()">Vestimentas</a>
            <a href="#" onclick="alterarImagens4()">Remedios</a>
        </div>
    </div>
 
   
    <div class="container d-flex justify-content-center">
            <div class="row"id="imagens">
                <div class="col-md 4 col-sm-12" id="produto" >
                    <img id="img1" src="multimidia/produtos/caixa-de-ferramenta.png" class="rounded float-left, rounded" alt="Bota Masculina">
                    <p class="caption">Caixa de Ferramenta BOSCH</p>
                </div>
                
                <div class="col-md 4 col-sm-12" id="produto">
                    <img id="img2"src="multimidia/produtos/esmerilhadeira-angular.png" class="rounded float-left, rounded" alt="Bota Masculina">
                    <p class="caption">Esmerilhadeira Angular Makita</p>
                </div>
                <div class="col-md 4 col-sm-12" id="produto">
                    <img id="img3" src="multimidia/produtos/lepecid-spray.png"  class="rounded float-left, rounded" alt="Caixa de Ferramenta">
                    <p class="caption">Lepecid Spray</p>
                </div>
                <div class="col-md 4 col-sm-12" id="produto">                    
                    <img id= "img4" src="multimidia/produtos/master-LP.png" class="rounded float-left, rounded" alt="Bota Masculina">
                    <p class="caption">Master LP</p>
                </div>
            </div>
            <div class="row" id="imagens">
                <div class="col-md 4 col-sm-12" id="produto">
                    <img id="img5" src="multimidia/produtos/fipronil-gado-de-corte.png"  class="rounded float-left, rounded">
                    <p class="caption">Fipronil Gado de Corte</p>
                </div>
                <div class="col-md 4 col-sm-12" id="produto">
                    <img id="img6" src="multimidia/produtos/escova-de-aco-circular.png"  class="rounded float-left, rounded">
                    <p class="caption">Escova de Aço Circular</p>
                </div>
                <div class="col-md 4 col-sm-12" id="produto">
                    <img id="img7" src="multimidia/produtos/racao-aves-postura.png"  class="rounded float-left, rounded">
                    <p class="caption">Ração Aves Postura</p>
                </div>
                <div class="col-md 4 col-sm-12" id="produto">                    
                    <img id="img8" src="multimidia/produtos/serra_tico-tico.png" class="rounded float-left, rounded">
                    <p class="caption">Serra Tico-Tico</p>
                </div>
            </div>
        </div>
 
    <!-- Imagens com Bootstrap -->
      
       
 
 
   
    <script>
    function alterarImagens1() {            
         // Array com novas URLs das imagens
         const novasImagens = [                
            "multimidia/produtos/racao-vaca.png",                
            "multimidia/produtos/racao-aves-postura.png",                
            "multimidia/produtos/racao-boi.png",                
            "multimidia/produtos/racao-cachorro.png",                
            "multimidia/produtos/racao-cavalo.png",                
            "multimidia/produtos/racao-porco.png",                
            "multimidia/produtos/error.png",
            "multimidia/produtos/error.png",  
        ];            
        // Alterar as imagens com base nos IDs
        for (let i = 1; i <= 8; i++) {
             document.getElementById('img' + i).src = novasImagens[i - 1];
             }
    }
    function alterarImagens2() {            
         // Array com novas URLs das imagens
         const novasImagens = [                
            "multimidia/produtos/escova-de-aco-circular.png",                
            "multimidia/produtos/esmerilhadeira-angular.png",                
            "multimidia/produtos/parafusadeira-makita.png",                
            "multimidia/produtos/caixa-de-ferramenta.png",                
            "multimidia/produtos/serra_tico-tico.png",                
            "multimidia/produtos/lepecid-spray.png",
            "multimidia/produtos/tupia-M3700B.png",
            "multimidia/produtos/error.png",
                       
        ];            
        // Alterar as imagens com base nos IDs
        for (let i = 1; i <= 8; i++) {
             document.getElementById('img' + i).src = novasImagens[i - 1];
             }
 
 
 
       
    }
 
    function alterarImagens3() {            
         // Array com novas URLs das imagens
         const novasImagens = [                
            "multimidia/produtos/sandalia-vestimento.png",                
            "multimidia/produtos/porta-celular-vestimento.png",              
            "multimidia/produtos/bota-masc-vestimento.png",                    
            "multimidia/produtos/bone-vestimento.png",                                  
            "multimidia/produtos/botina-vestimento.png",  
            "multimidia/produtos/error.png",
            "multimidia/produtos/sapatilha-vestimento.png",
            "multimidia/produtos/error.png",
                       
        ];            
        // Alterar as imagens com base nos IDs
        for (let i = 1; i <= 8; i++) {
             document.getElementById('img' + i).src = novasImagens[i - 1];
           
            }
 
 
 
        for (let i = 1; i <= 8; i++) {                
            const box = document.getElementById('img' + i);                
            // Muda apenas as imagens 1, 3, 5 e 7
            if (i % 2 !== 0) {                    
                if (box.style.width === '250px') {                        
                    box.style.width = '200px';
                    // Voltar para a largura original                    
                } else {                        
                        box.style.width = '250px';
                        // Nova largura
                }
            }
        }
                           
           
       
 
 
    }
 
 
    function alterarImagens4() {            
         // Array com novas URLs das imagens
         const novasImagens = [                
            "multimidia/produtos/fipronil-gado-de-corte.png",                
            "multimidia/produtos/mo-performance.png",              
            "multimidia/produtos/nex-gard-caes.png",                    
            "multimidia/produtos/master-LP.png",                                  
            "multimidia/produtos/treo-ace.png",  
            "multimidia/produtos/error.png",
            "multimidia/produtos/error.png",
            "multimidia/produtos/error.png",
                       
        ];            
        // Alterar as imagens com base nos IDs
        for (let i = 1; i <= 8; i++) {
             document.getElementById('img' + i).src = novasImagens[i - 1];
           
        }
    }
 
       
   
    </script>
 
</body>
</html>