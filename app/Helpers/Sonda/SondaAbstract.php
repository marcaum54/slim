<?php

namespace App\Helpers\Sonda;


abstract class SondaAbstract
{
    protected $posicaoAtual = null;

    const AMBIENTE_TAMANHO = 5;

    const SENTIDO_PARA_CIMA = 'C';
    const SENTIDO_PARA_DIREITA = 'D';
    const SENTIDO_PARA_BAIXO = 'B';
    const SENTIDO_PARA_ESQUERDA = 'E';

    const SENTIDOS = [
        self::SENTIDO_PARA_CIMA => 'moverParaCima',
        self::SENTIDO_PARA_DIREITA => 'moverParaDireita',
        self::SENTIDO_PARA_BAIXO => 'moverParaBaixo',
        self::SENTIDO_PARA_ESQUERDA => 'moverParaEsquerda',
    ];

    const COMANDO_MOVER = 'M';
    const COMANDO_GIRAR_90_DIREITA = 'GD';
    const COMANDO_GIRAR_90_ESQUERDA = 'GE';

    const COMANDOS = [
        self::COMANDO_MOVER => 'mover',
        self::COMANDO_GIRAR_90_DIREITA => 'girar90Direita',
        self::COMANDO_GIRAR_90_ESQUERDA => 'girar90Esquerda',
    ];

    const ERRO_COMANDO_NAO_EXISTE = "O comando '?' não existe";
    const ERRO_SENTIDO_NAO_EXISTE = "A posição '?' não existe";
    const ERRO_MOVER_LIMITE = "A sonda não pode mais se mover no eixo: ?";

    const JSON_PATH = __DIR__ . '/../../../sonda.json';

    abstract protected function getAmbiente();

    abstract protected function getPosicaoAtual();
    abstract protected function setPosicaoAtual($x, $y, $sentido);

    abstract protected function mover();
    abstract protected function girar90Direita();
    abstract protected function girar90Esquerda();
}
