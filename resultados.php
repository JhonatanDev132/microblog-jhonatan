<?php

use Microblog\Utilitarios;

require_once "inc/cabecalho.php";
$noticia->setTermo($_GET["busca"]);
$resultados = $noticia->busca();
Utilitarios::dump($resultados);
?>


<div class="row bg-white rounded shadow my-1 py-4">
    <h2 class="col-12 fw-light">
        Você procurou por <span class="badge bg-dark"><?=$noticia->getTermo()?></span>
        obteve <span class="badge bg-info"><?=count($resultados)?></span> resultados
    </h2>
    <?php foreach($resultados as $itemNoticia){ ?>
    <div class="col-12 my-1">
        <article class="card">
            <div class="card-body">
                <h3 class="fs-4 card-title fw-light"><?=$itemNoticia['titulo']?></h3>
                <p class="card-text">
                <?=Utilitarios::formataData($itemNoticia['data'])?> - 
                <?=$itemNoticia['resumo']?>
                </p>
                
                <a href="noticia.php?id=<?=$itemNoticia['id']?>" class="btn btn-primary btn-sm">Continuar lendo</a>
            </div>
        </article>
    </div>
    <?php } ?>

    <div class="col-12 my-1">
        <article class="card">
            <div class="card-body">
                <h3 class="fs-4 card-title fw-light">Título da notícia...</h3>
                <p class="card-text">
                    <time>Data da notícia</time> - 
                    Resumo da notícia.
                </p>
                
                <a href="noticia.php" class="btn btn-primary btn-sm">Continuar lendo</a>
            </div>
        </article>
    </div>

</div>        

<?php 
require_once "inc/todas.php";
require_once "inc/rodape.php";
?>

