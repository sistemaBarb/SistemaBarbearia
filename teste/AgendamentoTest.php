<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Models/Agendamento.php';

class AgendamentoTest extends TestCase
{
    public function testDetectarConflitoDeHorario() // teste para que verifica se o sistema bloqueia horários conflitantes
    {
        $mock = $this->createMock(PDOStatement::class);
        $mock->method('rowCount')->willReturn(1); // simula 1 agendamento encontrado
        $mock->method('execute')->willReturn(true);

        $bdmock = $this->createMock(PDO::class);
        $bdmock->method('prepare')->willReturn($mock);

        $agendamento = new Agendamento($bdmock);
        $resultado = $agendamento->verificarHorarioOcupado(44, '2026-06-11', '14:15:00', '14:45:00', 30); //dados reais do banco de dados, tendo em vista que o id do meu barbeiro corresponde ao 44 

        $this->assertTrue($resultado, "erro");
    }

    public function testPermitirAgendamentoEmHorarioLivre() // teste para verifica se o sistema libera horários vazios
    {
        $mock = $this->createMock(PDOStatement::class);
        $mock->method('rowCount')->willReturn(0); //simula que o sistema possue horarios livres 
        $mock->method('execute')->willReturn(true);

        $bdmock = $this->createMock(PDO::class);
        $bdmock->method('prepare')->willReturn($mock);

        $agendamento = new Agendamento($bdmock);
        $resultado = $agendamento->verificarHorarioOcupado(44, '2026-06-11', '09:00:00', '09:30:00', 30);

        $this->assertFalse($resultado, "erro");
    }
}
