<?php

namespace App\Helpers\Sonda;


trait SondaEspacialMovimentacaoTrait
{
    protected function girar90($orientacao)
    {
        $posicaoAtual = $this->getPosicaoAtual();

        $movimentacoes = array_keys(SondaAbstract::SENTIDOS);
        $movimentacoes_qtd = count($movimentacoes);

        $movimentacao = array_search($posicaoAtual['sentido'], $movimentacoes);

        if($orientacao === 'girar90Esquerda')
            $movimentacao -= 1;

        if($orientacao === 'girar90Direita')
            $movimentacao += 1;

        if($movimentacao >= $movimentacoes_qtd)
            $movimentacao = 0;

        if($movimentacao < 0)
            $movimentacao = $movimentacoes_qtd - 1;

        $this->setPosicaoAtual($posicaoAtual['x'], $posicaoAtual['y'], $movimentacoes[$movimentacao]);
    }

    protected function girar90Direita()
    {
        $this->girar90(__FUNCTION__);
    }
    
    protected function girar90Esquerda()
    {
        $this->girar90(__FUNCTION__);
    }

    protected function moverParaCima($posicaoAtual)
    {
        $this->setPosicaoAtual($posicaoAtual['x'], $posicaoAtual['y'] + 1, $posicaoAtual['sentido']);
    }

    protected function moverParaDireita($posicaoAtual)
    {
        $this->setPosicaoAtual($posicaoAtual['x'] + 1, $posicaoAtual['y'], $posicaoAtual['sentido']);
    }
    
    protected function moverParaBaixo($posicaoAtual)
    {
        $this->setPosicaoAtual($posicaoAtual['x'], $posicaoAtual['y'] - 1, $posicaoAtual['sentido']);
    }
    
    protected function moverParaEsquerda($posicaoAtual)
    {
        $this->setPosicaoAtual($posicaoAtual['x'] - 1, $posicaoAtual['y'], $posicaoAtual['sentido']);
    }
}
