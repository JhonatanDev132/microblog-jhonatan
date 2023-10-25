<?php
namespace Microblog;
use PDO, Exception;

use function PHPSTORM_META\type;

final class Noticia {
    private int $id;
    private string $data;
    private string $titulo;
    private string $texto;
    private string $resumo;
    private string $imagem;
    private string $destaque;
    private string $termo; // será usado na busca
    private PDO $conexao;

    /* Propriedades cujo tipo são ASSOCIADOS
    às classes já existentes. Isso permitirá usar
    recursos destas classes à partir de Notícia */
    public Usuario $usuario;
    public Categoria $categoria;

    public function __construct()
    {
        /* Ao criar um objeto notícia, aproveitamos para instanciar objetos de Usuarios e Categoria */
        $this->usuario = new Usuario;
        $this->categoria = new Categoria;
        $this->conexao = Banco::conecta();  
    }

    public function inserir() : void {
        $sql = "INSERT INTO noticias(
            titulo,
            texto,
            resumo,
            imagem,
            destaque,
            usuario_id,
            categoria_id
        )
        VALUES(
            :titulo,
            :texto,
            :resumo,
            :imagem,
            :destaque,
            :usuario_id,
            :categoria_id
        )";

        try{
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":titulo",$this->titulo, PDO::PARAM_STR);
            $consulta->bindValue(":texto",$this->texto, PDO::PARAM_STR);
            $consulta->bindValue(":resumo",$this->resumo, PDO::PARAM_STR);
            $consulta->bindValue(":imagem",$this->imagem, PDO::PARAM_STR);
            $consulta->bindValue(":destaque",$this->destaque, PDO::PARAM_STR);

            /* Aqui, primeiro chamamos os getter de ID do Usuario e de Categoria,
            para só depois associar os valores aos parâmetros da consulta SQL.
            Isso é possível devido à associação entre as Classes. */
            $consulta->bindValue(":usuario_id",$this->usuario->getId(), PDO::PARAM_INT);
            $consulta->bindValue(":categoria_id",$this->categoria->getId(), PDO::PARAM_INT);
            $consulta->execute();
        } catch(Exception $erro){
            die("Erro ao inserir notícia".$erro->getMessage());
        }
    }

    public function listar():array {
        /* Se o tipo de usuário logado for admin */
        if($this->usuario->getTipo() === 'admin') {
            // Considere o SQL abaixo (pega tudo de todos)
            $sql = "SELECT
            noticias.id,
            noticias.titulo,
            noticias.data, 
            usuarios.nome AS autor, 
            noticias.destaque FROM noticias INNER JOIN usuarios 
            ON noticias.usuario_id = usuarios.id ORDER BY data DESC";
        } else{

        // Senão, consifere o SQL abaixo (pega somente referente ao editor)
        
            $sql = "SELECT
            id,
            titulo,
            data,
            destaque FROM noticias
            WHERE usuario_id = :usuario_id ORDER BY data DESC";
        }
        
    }


    /* Método para upload de foto */

    public function upload(array $arquivo):void{
        // Definindo os tipos válidos
        $tiposValidos = [
            "image/png",
            "image/jpeg",
            "image/gif",
            "image/svg+xml"
        ];

        // Verificando se o arquivo não é um dos tipos validos
        if(!in_array($arquivo["type"], $tiposValidos)){
            // Alertamos o usuário e o fazemos voltar para o form.
            die("
                <script>
                alert('Formato inválido!');
                history.back();
                </script>;
                ");
        }

        // Acessando apenas o nome/extensão do arquivo
        $nome = $arquivo["name"];

        // Acessando os dados de acesso/armazenamento temporários
        $temporario = $arquivo["tmp_name"];

        // Definindo a pasta de destino das imagens no site
        $pastaFinal = "../imagens/".$nome;

        // Movemos/enviamos da área temporária para a final/destino
        move_uploaded_file($temporario, $pastaFinal);
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    }


    public function getData(): string
    {
        return $this->data;
    }


    public function setData(string $data): void
    {
        $this->data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
    }


    public function getTitulo(): string
    {
        return $this->titulo;
    }


    public function setTitulo(string $titulo): void
    {
        $this->titulo = filter_var($titulo, FILTER_SANITIZE_SPECIAL_CHARS);
    }


    public function getTexto(): string
    {
        return $this->texto;
    }


    public function setTexto(string $texto): void
    {
        $this->texto = filter_var($texto, FILTER_SANITIZE_SPECIAL_CHARS);
    }


    public function getResumo(): string
    {
        return $this->resumo;
    }


    public function setResumo(string $resumo): void
    {
        $this->resumo = filter_var($resumo, FILTER_SANITIZE_SPECIAL_CHARS);
    }


    public function getImagem(): string
    {
        return $this->imagem;
    }


    public function setImagem(string $imagem): void
    {
        $this->imagem = filter_var($imagem, FILTER_SANITIZE_SPECIAL_CHARS);
    }


    public function getDestaque(): string
    {
        return $this->destaque;
    }


    public function setDestaque(string $destaque): void
    {
        $this->destaque = filter_var($destaque, FILTER_SANITIZE_SPECIAL_CHARS);
    }


    public function getTermo(): string
    {
        return $this->termo;
    }


    public function setTermo(string $termo): void
    {
        $this->termo = filter_var($termo, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}