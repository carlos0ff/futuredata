<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrdemFactory extends Factory
{
    private static array $problemas = [
        'Notebook'   => [
            'Não liga após queda','Tela piscando e com linhas','Superaquecimento constante',
            'Teclado com teclas travadas','Bateria não carrega mais','Lentidão extrema no sistema',
        ],
        'Celular' => [
            'Tela quebrada após queda','Não carrega pela entrada USB','Câmera com imagem embaçada',
            'Microfone não funciona','Sem sinal de rede','Reinicializando sozinho constantemente',
        ],
        'PC' => [
            'Não inicializa o Windows','Travando com frequência','Sem vídeo ao ligar',
            'Barulho alto no HD','Ventilador fazendo ruído','Placa de rede não detectada',
        ],
        'Impressora' => [
            'Papel atolando com frequência','Impressão com listras horizontais','Não é reconhecida pelo PC',
            'Cabeça de impressão entupida','Erro de cartucho vazio mesmo cheio','Não conecta via Wi-Fi',
        ],
        'Monitor' => [
            'Tela piscando ao ligar','Sem imagem mas com LED aceso','Linhas verticais na tela',
            'Brilho oscilando sozinho','Entrada HDMI não funciona','Pixel morto no centro',
        ],
    ];

    private static array $diagnosticos = [
        'Substituição do HD por SSD — aumento de performance significativo.',
        'Troca da bateria original — autonomia restaurada.',
        'Limpeza de vírus e otimização do sistema operacional.',
        'Troca do cabo flat da tela — imagem restaurada.',
        'Limpeza interna e troca da pasta térmica do processador.',
        'Formatação e reinstalação do sistema operacional.',
        'Substituição do teclado — modelo original aplicado.',
        'Reflow da placa-mãe — soldas refluxadas com sucesso.',
        'Troca do carregador original — carga normalizada.',
        'Atualização de drivers e configuração de rede.',
    ];

    public function definition(): array
    {
        $cliente    = Cliente::inRandomOrder()->first();
        $equipamento = Equipamento::where('cliente_id', $cliente?->id)->first()
                    ?? Equipamento::inRandomOrder()->first();

        $tipo    = $equipamento?->tipo ?? 'Notebook';
        $problemas = self::$problemas[$tipo] ?? self::$problemas['Notebook'];

        $status = $this->faker->randomElement([
            'entrada','analise','execucao','aguardando_cliente','em_teste','finalizado','cancelado',
        ]);

        $createdAt = $this->faker->dateTimeBetween('-6 months', 'now');

        return [
            'cliente_id'       => $cliente?->id,
            'equipamento_id'   => $equipamento?->id,
            'tecnico_id'       => User::first()?->id,
            'status'           => $status,
            'problema_relatado'=> $this->faker->randomElement($problemas),
            'diagnostico'      => $this->faker->optional(0.7)->randomElement(self::$diagnosticos),
            'solucao'          => $status === 'finalizado'
                                  ? 'Serviço concluído. Equipamento testado e aprovado.'
                                  : null,
            'valor_servico'    => $this->faker->randomElement([80, 120, 150, 180, 200, 250, 300, 350, 400, 500]),
            'valor_pecas'      => $this->faker->randomElement([0, 0, 0, 50, 80, 100, 150, 200]),
            'desconto'         => $this->faker->randomElement([0, 0, 0, 0, 10, 20, 30]),
            'previsao_entrega' => $this->faker->dateTimeBetween('now', '+15 days'),
            'finalizado_em'    => $status === 'finalizado' ? $createdAt : null,
            'created_at'       => $createdAt,
            'updated_at'       => $createdAt,
        ];
    }
}
