# Portal do Cliente — Páginas Demo

Conjunto de páginas HTML estáticas que demonstram todos os estados e fluxos do **Portal do Cliente** da AssistPro. Todas as páginas compartilham o mesmo design system (variáveis CSS, navbar, footer, tipografia Inter).

---

## Páginas

### 1. `portal-demo.html` — Detalhe da OS (Em andamento)
Página principal de acompanhamento de uma Ordem de Serviço com orçamento **aguardando aprovação** e status **Em teste**.

**Contém:**
- Hero com número da OS, status e data de abertura
- Timeline de etapas (Recebimento → Análise → Em teste → Finalização → Entrega)
- Card de orçamento com tabela de itens e botões Aprovar / Recusar
- Fotos e documentos do reparo (link para o laudo técnico)
- Coluna direita: equipamento, defeito relatado, previsão de entrega, técnico, ajuda e chat

**Links para:** `portal-demo-lista.html`, `portal-demo-laudo.html`, `portal-demo-mensagens.html`

---

### 2. `portal-demo-lista.html` — Lista de todas as OS
Visão geral de todas as Ordens de Serviço do cliente.

**Contém:**
- Hero com estatísticas (total, em aberto, concluídas)
- Barra de filtros: busca por texto + pills de status (Todas / Em aberto / Concluídas)
- Cards de OS com: ícone do equipamento, número, badge de status, descrição, técnico, valor, datas, barra de progresso e mini-tira de etapas
- Filtro funcional via JavaScript (busca + status simultâneos)
- Estado vazio quando nenhum resultado é encontrado

**OS de exemplo:**
| OS | Equipamento | Status |
|----|-------------|--------|
| #12458 | Notebook Dell Inspiron 15 | Em teste (60%) |
| #12391 | Samsung Galaxy S22 | Aguardando peça (35%) |
| #12270 | Notebook Dell Inspiron 15 | Concluída |
| #11987 | Impressora HP LaserJet Pro | Concluída |
| #11742 | Smartphone iPhone 12 | Concluída |

---

### 3. `portal-demo-recebimento.html` — OS recém aberta (sem orçamento)
Estado inicial de uma OS logo após o recebimento do equipamento, **antes do diagnóstico**.

**Contém:**
- Hero roxo com badge "Recebido"
- Banner informativo dentro da timeline
- Timeline com apenas "Recebimento" como etapa ativa; demais pendentes
- Card de orçamento vazio: estado explicativo com 3 passos (Recebido → Diagnóstico → Orçamento)
- Fotos: 1 real (recebimento) + 3 slots vazios com fundo listrado
- Checklist de recebimento (✅ feito / ○ pendente)
- Card "Próxima etapa" destacado em roxo

---

### 4. `portal-demo-laudo.html` — Laudo Técnico
Documento técnico formal gerado após o diagnóstico, acessível pelo cliente.

**Contém:**
- Cabeçalho do documento com logo, CNPJ e número do laudo (`LT-2024-0158`)
- 8 seções numeradas:
  1. Identificação (OS, data, técnico, cliente)
  2. Equipamento (marca, modelo, série, specs)
  3. Defeito relatado (citação do cliente)
  4. Diagnóstico técnico (4 cards + causa raiz + barra de gravidade)
  5. Registro fotográfico (6 fotos com tags coloridas)
  6. Peças e serviços (tabela com referências + totalizador)
  7. Conclusão, garantia de 90 dias e recomendações
  8. Assinaturas (técnico + campo aguardando cliente)
- Rodapé com data de emissão, validade e QR code placeholder
- Botão **Imprimir / Baixar PDF** (`window.print()`) com CSS de impressão

---

### 5. `portal-demo-recusado.html` — Orçamento Recusado
Estado da OS após o cliente **recusar o orçamento**.

**Contém:**
- Hero com gradiente vermelho escuro e badge "Orçamento recusado"
- Card de motivo da recusa: 4 opções selecionáveis + campo de observação
- Sugestão de negociação via WhatsApp
- Timeline histórica até a recusa, com ícone ✕ vermelho
- Orçamento riscado (itens com `text-decoration: line-through`)
- **Taxa de diagnóstico** destacada em âmbar (R$ 80,00 — cobrada mesmo na recusa)
- 4 próximos passos: agendar retirada → documento → pagar taxa → retirar equipamento
- Aviso: equipamento guardado por até 30 dias

---

### 6. `portal-demo-mensagens.html` — Mensagens (Ver todas)
Interface de chat completa com histórico de todas as conversas por OS.

**Layout 3 colunas:**

| Coluna | Conteúdo |
|--------|----------|
| Esquerda (280px) | Lista de conversas agrupadas por OS, com badge de não lidas e preview |
| Centro | Janela de mensagens com scroll |
| Direita (280px) | OS relacionadas, participantes, resumo da OS e link para WhatsApp |

**Tipos de mensagem suportados:**
- Texto simples (tech ↔ cliente)
- Evento de sistema (recebimento, orçamento enviado)
- Orçamento como card clicável com botão "Ver laudo"
- Arquivo / PDF com botão de download
- Foto com indicador de aprovação

**Funcionalidades JavaScript:**
- Envio com Enter (Shift+Enter = quebra de linha)
- Auto-resize do textarea
- Indicador "digitando..." animado
- Resposta automática simulada após 2 segundos
- Scroll automático para a última mensagem

---

## Navegação entre páginas

```
portal-demo-lista.html          ← ponto de entrada (lista de OS)
    └── portal-demo.html        ← detalhe da OS em andamento
          ├── portal-demo-laudo.html       ← laudo técnico (PDF)
          ├── portal-demo-recusado.html    ← se recusar orçamento
          └── portal-demo-mensagens.html  ← ver todas as mensagens

portal-demo-recebimento.html    ← OS recém aberta (sem orçamento ainda)
```

---

## Design System

Todas as páginas usam as mesmas variáveis CSS definidas em `:root`:

| Variável | Uso |
|----------|-----|
| `--blue` / `--blue-light` | Ações principais, links, destaques |
| `--green` / `--green-bg` | Sucesso, aprovação, concluído |
| `--amber` / `--amber-bg` | Atenção, em andamento, avisos |
| `--red` / `--red-bg` | Erro, recusa, defeito |
| `--slate-*` | Textos, bordas, fundos neutros |
| `--wa-green` | Botões e elementos WhatsApp |
| `--dark` | Navbar e footer |
| `--radius` | Border-radius padrão (14px) |

**Fontes:** Inter (Google Fonts) · **Ícones:** Font Awesome 6.5

---

## Responsividade

| Breakpoint | Comportamento |
|------------|---------------|
| `> 900px` | Layout completo com 2 colunas |
| `≤ 900px` | Coluna única, hero empilhado |
| `≤ 600px` | Navbar simplificada, elementos secundários ocultados |
| `≤ 760px` | Chat: painel de conversas ocultado (foco na janela) |
| `@print` | Laudo: oculta navbar, hero e sidebar — imprime apenas o documento |
