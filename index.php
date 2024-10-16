
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Agro Malandrin</title>
    <style>
    #foto2 #titulo google-logo {
    font-size: 1.8em;
    font-weight: bold;
    cursor: pointer;
    display: inline-block;
}

.google-logo span {
    transition: text-shadow 0.3s ease-in-out, transform 0.3s ease-in-out;
}

/* Cores da logo do Google */
.g {
    color: #4285F4;
}
.o1 {
    color: #EA4335;
}
.o2 {
    color: #FBBC05;
}
.g2 {
    color: #4285F4;
}
.l {
    color: #34A853;
}
.e {
    color: #EA4335;
}

/* Efeito de brilho ao passar o mouse */
.google-logo:hover span {
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
    transform: scale(1.1);
}
    </style>
</head>


<?php
include_once 'includes/header.php'
?>

<marquee> visite-nos em Nossa Loja </marquee>

<main id="home">
    
    <picture>
        <source media="(min-width: 992px)" srcset="multimidia/logo/Logo_Home.png">
        <img src="multimidia/logo_Home_mob.png" alt="logo Agro malandrin" id="logo">
    </picture>

    <p class="title">Nossa Historia</p>
    
    <p id="apresentacao">Somos uma empresa que atua desde 2012, na cidade de Itapira no Interior de São Paulo, 
        Nosso ramo de atuação é a venda e distribuição de rações para diversos tipos de animais, como 
        equinos, suinos, ovinos, bovinos e aves. <br> <br>
        
        Também vendemos equipamentos de serviço rural, utensílios de jardinagem, materiais pra piscina, 
        telas de galinheiro e muito mais.<br> 
    
        Muito antes de termos essa agro, a mais ou menos 28 anos atrás, o dono José Francisco Malandrin, 
        possuia um barracão para realizar seu comércio, vendendo grãos como feijão, arroz, etc.<br> <br>

        Os anos foram se passando e ele construíu uma grande agro, cujo o sobrenome da família será lembrado 
        como marca registrada no nome da agro. 
    </p>


        <div class="container" >
            <div class="row">
                <!--  foto1 -->
                <div class="col-12 d-flex justify-content-between align-items-center" id="foto1">
                
                    <div id="foto"> 
                        <img src="multimidia/logo/faixada_AG.png" width="400px" alt="faixada da Agro">

                        <p id="descricao">Rua Joaquim de Souza Dias - São Vincente, Itapira-SP</p>
                    </div>
                
                    <div class="container_texto text-center me-3" id="text">
                        <h4 class="texto-titulo">ANOS DE SERVIÇO</h4>
                        <p class="texto-menor" id="menor">
                            Nossa agro ja está <br>
                             em funcionamento a <br>
                             a mais de uma decada! visite-nos!
                            </p>
                    </div>

                    
                </div>
                

                <!-- foto2 -->
                <div class="col-12 d-flex justify-content-between align-items-center" id="foto2">
                    
                    <div class="container_texto text-center">
                        <h4 class="texto-titulo">Facil localização</h4>
                        <p class="texto-menor">
                            para acessar o Google Maps e obter mais informações sobre como chegar até nós.
                        </p>
                    </div>    

                    <a href="https://www.google.com/maps/place/AGRO+MALANDRIN+DE+ITAPIRA+LTDA/@-22.447972,-46.8297805,17z/data=!4m14!1m7!3m6!1s0x94c8fdf2152fa27b:0xee9600c02ad02357!2sAGRO+MALANDRIN+DE+ITAPIRA+LTDA!8m2!3d-22.447977!4d-46.8272056!16s%2Fg%2F11frfsxnmw!3m5!1s0x94c8fdf2152fa27b:0xee9600c02ad02357!8m2!3d-22.447977!4d-46.8272056!16s%2Fg%2F11frfsxnmw?entry=ttu&g_ep=EgoyMDI0MDgyMS4wIKXMDSoASAFQAw%3D%3D" id="linkMaps">
                        <table id="maps">
                            <th id="titulo">                    
                                <div class="google-logo">
                                    <span class="g">G</span><span class="o1">o</span><span class="o2">o</span>
                                    <span class="g2">g</span><span class="l">l</span><span class="e">e</span>
                                    maps
                                </div>
                            </th>
                            <tr>
                                <td>
                                    <div id="espacamento"><img src="multimidia/logo/maps.png" alt="foto do google maps" id="imagem"></div>
                                </td>
                            </tr>
                        </table>
                    </a>                      
                    
                </div>


                <!-- foto3 -->
                <div class="col-12 d-flex justify-content-between align-items-center" id="avaliacao">
                    
                    <table id="tabel">
                        <th id="titulo"> <a href="https://www.google.com/search?gs_ssp=eJzj4tVP1zc0TCtKK67Iyy03YLRSNaiwNEm2SEtJMzI0NUpLNDJPsjKoSE21NDMwSDYwSkwxMDI2NffiS0wvylfITcxJzEspyswDAObcFWM&q=agro+malandrin&rlz=1C1GCEA_enBR1115BR1115&oq=agro+ma&gs_lcrp=EgZjaHJvbWUqEAgCEC4YrwEYxwEYgAQYjgUyBggAEEUYOTIGCAEQRRg7MhAIAhAuGK8BGMcBGIAEGI4FMgcIAxAAGIAEMgcIBBAAGIAEMgYIBRBFGDwyBggGEEUYPDIGCAcQRRg80gEINzI4N2owajeoAgCwAgA&sourceid=chrome&ie=UTF-8#lrd=0x94c8fdf2152fa27b:0xee9600c02ad023divid="link"> NOS AVALIE AQUI!!</a> </th>
                        
                        <tr>
                            <td>
                                <p id="texto"> Prezado usuário, após clicar no link acima, você <br> 
                                será direcionado para as avaliações dos nossos <br>
                                serviços da nossa página no Google, nos ajude <br>
                                com o feedback para conseguirmos melhorar  a <br>
                                sua experiência.
                                </p>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>

    

    <div id="foto2">

      

        
    </div>
</main>

<?php
include_once 'includes/footer.php';
?>