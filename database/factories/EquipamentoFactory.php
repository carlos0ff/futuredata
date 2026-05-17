<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipamentoFactory extends Factory
{
    private static array $equipamentos = [
        'Notebook'   => [
            'marcas' => ['Dell','Lenovo','HP','Asus','Acer','Apple','Samsung'],
            'modelos' => ['Inspiron 15','ThinkPad E14','Pavilion 14','Vivobook 15','Aspire 5','MacBook Air','Galaxy Book'],
        ],
        'Celular' => [
            'marcas' => ['Samsung','Apple','Motorola','Xiaomi','LG','Nokia','Realme'],
            'modelos' => ['Galaxy S23','iPhone 14','Moto G73','Redmi Note 12','K52','G22','C35'],
        ],
        'PC' => [
            'marcas' => ['Dell','HP','Positivo','Lenovo','Multilaser','CCE','Acer'],
            'modelos' => ['OptiPlex 3080','EliteDesk 800','Master D550','IdeaCentre 5','Info Premium','MP-2821','Veriton N'],
        ],
        'Impressora' => [
            'marcas' => ['HP','Epson','Canon','Brother','Samsung','Lexmark','Xerox'],
            'modelos' => ['LaserJet Pro','EcoTank L3150','PIXMA G3010','DCP-L2532DW','Xpress M2020','MB2236adw','C235'],
        ],
        'Monitor' => [
            'marcas' => ['LG','Samsung','Dell','AOC','Philips','BenQ','Acer'],
            'modelos' => ['27UP850','Odyssey G5','U2722D','24B2XH','24E1N1300','GW2480','KG271'],
        ],
    ];

    public function definition(): array
    {
        $tipo   = $this->faker->randomElement(array_keys(self::$equipamentos));
        $config = self::$equipamentos[$tipo];
        $idx    = $this->faker->numberBetween(0, count($config['marcas']) - 1);

        return [
            'cliente_id'       => Cliente::inRandomOrder()->first()?->id ?? Cliente::factory(),
            'tipo'             => $tipo,
            'marca'            => $config['marcas'][$idx],
            'modelo'           => $config['modelos'][$idx],
            'numero_serie'     => strtoupper($this->faker->bothify('??##-####-??')),
            'patrimonio'       => 'PAT-' . $this->faker->numerify('#####'),
            'acessorios'       => $this->faker->randomElement([
                'Carregador','Carregador e cabo','Sem acessórios','Mochila e carregador','Fonte original',
            ]),
            'condicao_entrada' => $this->faker->randomElement([
                'Sem avarias externas visíveis',
                'Tela com pequeno risco',
                'Carcaça com amassado lateral',
                'Tampa traseira trincada',
                'Bom estado geral',
            ]),
            'em_garantia'      => $this->faker->boolean(25),
        ];
    }
}
