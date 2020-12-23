<?php

namespace app\Helpers;

use App\Helpers\Json;
use App\Helpers\Sonda\SondaAbstract;
use App\Helpers\Sonda\SondaEspacialMovimentacaoTrait;

class SondaEspacial extends SondaAbstract
{
    use SondaEspacialMovimentacaoTrait;

    public function __construct()
    {
        $posicaoAtual = $this->getPosicaoAtual();

        if(!$posicaoAtual)
            $this->setPosicaoAtual(0, 0, SondaAbstract::SENTIDO_PARA_DIREITA);
    }

    protected function getAmbiente()
    {
        try
        {
            $ambiente = Json::ler(self::JSON_PATH);
        }
        catch(\Exception $e)
        {
            $ambiente = [];
        
            $range = range(0, self::AMBIENTE_TAMANHO - 1);

            foreach($range as $y)
            {
                if(!isset($ambiente[$y]))
                    $ambiente[$y] = [];

                foreach($range as $x)
                    $ambiente[$y][$x] = null;
            }

            $ambiente = $this->salvar($ambiente);
        }

        return $ambiente;
    }

    public function getPosicaoAtual()
    {
        $ambiente = $this->getAmbiente();

        foreach($ambiente as $y => $colunas)
        {
            foreach($colunas as $x => $sentido)
            {
                if($sentido)
                    return compact('x', 'y', 'sentido');
            }
        }

        return null;
    }

    protected function setPosicaoAtual($x, $y, $sentido)
    {
        if($x < 0 || $x >= self::AMBIENTE_TAMANHO)
            throw new \Exception(str_replace('?', 'X', self::ERRO_MOVER_LIMITE));

        if($y < 0 || $y >= self::AMBIENTE_TAMANHO)
            throw new \Exception(str_replace('?', 'Y', self::ERRO_MOVER_LIMITE));

        $ambiente = $this->getAmbiente();
        $posicaoAtual = $this->getPosicaoAtual();

        if($posicaoAtual)
            $ambiente[$posicaoAtual['y']][$posicaoAtual['x']] = null;

        $ambiente[$y][$x] = $sentido;

        return $this->salvar($ambiente);
    }

    protected function salvar(array $ambiente)
    {
        return Json::escrever(self::JSON_PATH, $ambiente);
    }
    
    public function resetar()
    {
        return Json::deletar(self::JSON_PATH);
    }

    protected function validarComando($comando)
    {
        if(!in_array($comando, array_keys(self::COMANDOS)))
            throw new \Exception(str_replace('?', $comando, self::ERRO_COMANDO_NAO_EXISTE));
    }

    protected function mover()
    {
        $posicaoAtual = $this->getPosicaoAtual();
        $metodo = self::SENTIDOS[$posicaoAtual['sentido']];

        $this->$metodo($posicaoAtual);
    }

    public function executarComandos(Array $comandos, $debug = false)
    {
        try
        {
            $posicaoAtual = $this->getPosicaoAtual();

            if($debug)
                $this->debug();

            foreach($comandos as $comando)
            {
                $this->validarComando($comando);

                $metodo = self::COMANDOS[$comando];
                $this->$metodo();

                if($debug)
                    $this->debug();
            }

            return $this->getPosicaoAtual();
        }
        catch(\Exception $e)
        {
            $this->setPosicaoAtual($posicaoAtual['x'], $posicaoAtual['y'], $posicaoAtual['sentido']);
            return [
                'erro' => $e->getMessage(),
            ];
        }
    }

    public function debug()
    {
        $sentidos = [
            'C' => '&uarr;',
            'D' => '&rarr;',
            'B' => '&darr;',
            'E' => '&larr;',
        ];

        $ambiente = $this->getAmbiente();

        $reserso = array_reverse($ambiente);

        echo '<table style="float: left; margin: 0 10px 10px 0; border: 1px solid #333; border-collapse: collapse;">';
        foreach($reserso as $y => $colunas)
        {
            echo '<tr>';
            foreach($colunas as $x => $sentido)
            {
                $r = SondaAbstract::AMBIENTE_TAMANHO - $y - 1;
                $css = $sentido ? 'background-color: red; color: #FFF;': '';
                echo '<td style="padding: 5px; text-align: center; border: 1px solid #333; '. $css .'">';
                echo $sentido ? $sentidos[$sentido] : "({$x}, $r)";
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }
}
