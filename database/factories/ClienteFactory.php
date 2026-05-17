<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    public function definition(): array
    {
        $nomes = [
            'Ana Paula Souza','Carlos Eduardo Lima','Fernanda Oliveira','Marcos Vinicius Santos',
            'Juliana Ferreira','Rafael Costa','Patricia Alves','Bruno Rodrigues','Camila Martins',
            'Diego Pereira','Larissa Nascimento','Gustavo Araújo','Tatiane Gomes','Felipe Carvalho',
            'Aline Ribeiro','Rodrigo Mendes','Vanessa Cunha','Anderson Barbosa','Priscila Moreira',
            'Leonardo Cardoso','Bruna Teixeira','Thiago Nunes','Sabrina Castro','Renato Freitas',
            'Letícia Monteiro','Fabio Correia','Débora Lopes','Marcelo Silva','Natália Azevedo',
            'Paulo Henrique Torres',
        ];

        $cidades = [
            'São Paulo','Rio de Janeiro','Belo Horizonte','Salvador','Fortaleza',
            'Curitiba','Manaus','Recife','Porto Alegre','Goiânia',
            'Belém','Guarulhos','Campinas','São Luís','Maceió',
            'Natal','Teresina','Campo Grande','João Pessoa','Aracaju',
        ];

        $estados = [
            'São Paulo' => 'SP', 'Rio de Janeiro' => 'RJ', 'Belo Horizonte' => 'MG',
            'Salvador' => 'BA', 'Fortaleza' => 'CE', 'Curitiba' => 'PR',
            'Manaus' => 'AM', 'Recife' => 'PE', 'Porto Alegre' => 'RS', 'Goiânia' => 'GO',
            'Belém' => 'PA', 'Guarulhos' => 'SP', 'Campinas' => 'SP', 'São Luís' => 'MA',
            'Maceió' => 'AL', 'Natal' => 'RN', 'Teresina' => 'PI', 'Campo Grande' => 'MS',
            'João Pessoa' => 'PB', 'Aracaju' => 'SE',
        ];

        $nome   = $this->faker->unique()->randomElement($nomes);
        $cidade = $this->faker->randomElement($cidades);
        $ddd    = $this->faker->randomElement(['11','21','31','41','51','61','71','81','85','91']);

        return [
            'nome'     => $nome,
            'email'    => $this->gerarEmail($nome),
            'telefone' => "({$ddd}) 9" . $this->faker->numerify('####-####'),
            'cpf_cnpj' => $this->gerarCpf(),
            'endereco' => 'Rua ' . $this->faker->randomElement(['das Flores','XV de Novembro','Sete de Setembro','Tiradentes','Marechal Deodoro']) . ', ' . $this->faker->numberBetween(1, 999),
            'cidade'   => $cidade,
            'estado'   => $estados[$cidade] ?? 'SP',
            'cep'      => $this->faker->numerify('#####-###'),
        ];
    }

    private function gerarEmail(string $nome): string
    {
        $partes   = explode(' ', strtolower($nome));
        $dominios = ['gmail.com', 'hotmail.com', 'yahoo.com.br', 'outlook.com', 'icloud.com'];
        return $partes[0] . '.' . end($partes) . $this->faker->numberBetween(1, 99)
             . '@' . $this->faker->randomElement($dominios);
    }

    private function gerarCpf(): string
    {
        $n = fn() => $this->faker->numberBetween(0, 9);
        $d = array_map(fn() => $n(), range(1, 9));
        $s1 = 0;
        foreach ($d as $i => $v) $s1 += $v * (10 - $i);
        $r1 = ($s1 % 11 < 2) ? 0 : 11 - ($s1 % 11);
        $d[] = $r1;
        $s2 = 0;
        foreach ($d as $i => $v) $s2 += $v * (11 - $i);
        $r2 = ($s2 % 11 < 2) ? 0 : 11 - ($s2 % 11);
        $d[] = $r2;
        return vsprintf('%d%d%d.%d%d%d.%d%d%d-%d%d', $d);
    }
}
