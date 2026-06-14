# Mensagens WhatsApp — FutureData Assistência Técnica

Catálogo completo das mensagens enviadas pelo WhatsApp ao cliente em cada etapa da OS.

> **Legenda de variáveis**
> - `{nome}` — primeiro nome do cliente
> - `{nome_completo}` — nome completo do cliente
> - `{equipamento}` — nome/modelo do equipamento
> - `{os}` — número da OS (ex: `OS202500001`)
> - `{data_entrada}` — data e hora de entrada (ex: `23/05/2025 às 14:30`)
> - `{link_portal}` — URL do portal com token da OS
> - `{cpf}` — CPF do cliente
> - `{data_nascimento}` — data de nascimento do cliente (senha do portal)
> - `{valor_orcamento}` — valor total do orçamento em R$
> - `{diagnostico}` — texto do diagnóstico técnico

---

## 1. Entrada da OS (Boas-vindas)

**Quando:** imediatamente após o cadastro da OS no sistema.  
**Gatilho:** `OrdemServicoController@store` ou botão "Enviar via WhatsApp" na tela da OS.  
**Arquivo:** `app/Http/Controllers/Portal/PortalController.php` e `app/Http/Controllers/App/OrdemServicoController.php`

### Versão portal (formatada com negrito WhatsApp)

```
Olá, *{nome}*! 👋

Recebemos seu *{equipamento}* aqui na assistência. ✅

📋 *OS:* {os}
📅 *Entrada:* {data_entrada}

Acompanhe o andamento pelo nosso *Portal do Cliente*:
🔗 {link_portal}

*Como acessar:*
1️⃣ Clique no link acima
2️⃣ Digite seu *CPF*
3️⃣ Informe sua *data de nascimento*
4️⃣ Pronto! Veja o status em tempo real

📱 Dúvidas? É só chamar aqui no WhatsApp.
— _AssistPro Assistência Técnica_
```

### Versão sistema interno (texto simples)

```
Olá, {nome_completo}!
Seu equipamento foi recebido com sucesso em nossa assistência técnica.

📄 OS: {os}
📱 Equipamento: {equipamento}
📍 Status atual: Equipamento recebido

Para acompanhar sua OS, acesse o portal do cliente:
CPF: {cpf}
Senha: sua data de nascimento ({data_nascimento})

🔗 Link do portal: {link_portal}

Em caso de dúvidas, estamos à disposição.
```

---

## 2. Mudança de Status

**Quando:** ao alterar o status da OS no sistema.  
**Gatilho:** `OrdemServicoController@update` / `updateStatus` — método `notificarMudancaStatus`.  
**Observação:** as mensagens abaixo são templates sugeridos. Hoje o sistema notifica apenas usuários internos (gerentes/técnicos) via notificação in-app; a integração de envio automático ao cliente via WhatsApp pode ser adicionada usando estes textos.

### 2.1 Em Análise (`analise`)

```
Olá, *{nome}*! 🔍

Seu *{equipamento}* está sendo analisado pela nossa equipe técnica.

📋 *OS:* {os}
📍 *Status:* Em análise

Assim que o diagnóstico estiver pronto, você será informado.

Acompanhe em tempo real:
🔗 {link_portal}
```

### 2.2 Diagnóstico Concluído — Aguardando Aprovação de Orçamento (`aguardando_cliente`)

```
Olá, *{nome}*! 📋

O diagnóstico do seu *{equipamento}* foi concluído.

📋 *OS:* {os}
🔧 *Diagnóstico:* {diagnostico}
💰 *Valor do serviço:* R$ {valor_orcamento}

Para prosseguir com o reparo, precisamos da sua aprovação.

👉 Acesse o portal para *Aprovar* ou *Recusar* o orçamento:
🔗 {link_portal}

📱 Prefere falar conosco? É só chamar aqui no WhatsApp.
```

### 2.3 Em Execução (`execucao`)

```
Olá, *{nome}*! 🔧

Ótimas notícias! O reparo do seu *{equipamento}* já está em andamento.

📋 *OS:* {os}
📍 *Status:* Em execução

Acompanhe o progresso pelo portal:
🔗 {link_portal}
```

### 2.4 Em Teste (`em_teste`)

```
Olá, *{nome}*! ✅

O reparo do seu *{equipamento}* foi concluído e está em fase de testes.

📋 *OS:* {os}
📍 *Status:* Em teste

Em breve você receberá a confirmação de que está pronto!

Acompanhe pelo portal:
🔗 {link_portal}
```

### 2.5 Finalizado — Pronto para Retirada (`finalizado`)

```
Olá, *{nome}*! 🎉

Seu *{equipamento}* está *pronto para retirada*!

📋 *OS:* {os}
📍 *Status:* Finalizado ✅

Estamos aguardando você em nossa loja.

Dúvidas? Chame aqui no WhatsApp ou acesse:
🔗 {link_portal}

Obrigado pela confiança! 😊
Future Data - Assistência Técnica
```

### 2.6 Cancelado (`cancelado`)

```
Olá, *{nome}*. 

Informamos que a OS referente ao seu *{equipamento}* foi *cancelada*.

📋 *OS:* {os}

Em caso de dúvidas, entre em contato conosco pelo WhatsApp ou acesse:
🔗 {link_portal}

Agradecemos o contato.
Future Data - Assistência Técnica
```

---

## 3. Orçamento

**Quando:** cliente acessa o portal e clica em "Aprovar via WhatsApp" ou "Recusar via WhatsApp".  
**Gatilho:** botões na view `resources/views/portal/show.blade.php`.  
**Observação:** estas mensagens são pré-preenchidas e abertas no WhatsApp do cliente — ele as envia manualmente para a assistência.

### 3.1 Aprovação de orçamento (cliente → assistência)

```
Olá! Quero aprovar o orçamento da {os}

```

### 3.2 Recusa de orçamento (cliente → assistência)

```
Olá! Quero recusar o orçamento da {os}

```

---

## 4. Mensagens do Cliente (Portal → Assistência)

**Quando:** cliente envia mensagem pelo chat do portal.  
**Gatilho:** `MessageController@store`  
**Destino:** notificação interna para gerentes e técnico responsável.

```
[Mensagem livre digitada pelo cliente no portal]
```

> O conteúdo desta mensagem é livre — o cliente digita diretamente no chat do portal. Não há template fixo.

---

## 5. Dúvida sobre OS (Portal Acompanhar)

**Quando:** cliente clica em "Fale conosco" na página de acompanhamento.  
**Arquivo:** `resources/views/portal/acompanhar.blade.php`

```
Olá! Consultei o portal e tenho uma dúvida sobre minha OS {os}
```

---

## 6. Compartilhar Link do Portal (Sistema Interno)

**Quando:** técnico/atendente envia o link do portal para o cliente pelo sistema.  
**Arquivo:** `resources/views/app/ordens/show.blade.php`

```
Olá! Acesse o portal para acompanhar sua OS: {link_portal}
```

---

## Resumo — Fluxo de Mensagens por Status

| Status               | Label               | Mensagem recomendada       | Enviada por         |
|----------------------|---------------------|----------------------------|---------------------|
| `entrada`            | Entrada registrada  | Seção 1 — Boas-vindas      | Sistema / manual    |
| `analise`            | Em análise          | Seção 2.1                  | Manual (sugerido)   |
| `aguardando_cliente` | Aguardando cliente  | Seção 2.2 — Orçamento      | Manual (sugerido)   |
| `execucao`           | Em execução         | Seção 2.3                  | Manual (sugerido)   |
| `em_teste`           | Em teste            | Seção 2.4                  | Manual (sugerido)   |
| `finalizado`         | Finalizado          | Seção 2.5 — Retirada       | Manual (sugerido)   |
| `cancelado`          | Cancelado           | Seção 2.6                  | Manual (sugerido)   |

> **"Manual (sugerido)"** = o sistema ainda não envia automaticamente para o cliente ao mudar o status. A mensagem deve ser copiada e enviada manualmente, ou pode ser integrada via API (Evolution API, Z-API, etc.).

---

## Implementação Técnica Atual

| Arquivo | Responsabilidade |
|---------|-----------------|
| `app/Http/Controllers/Portal/PortalController.php` | Gera `$waUrl` com mensagem de boas-vindas formatada para o portal |
| `app/Http/Controllers/App/OrdemServicoController.php` | Gera `$waLink` com mensagem de entrada ao criar OS; método `notificarMudancaStatus` notifica internamente |
| `resources/views/portal/show.blade.php` | Botões "Aprovar via WhatsApp" e "Recusar via WhatsApp" com mensagens pré-preenchidas |
| `resources/views/app/ordens/show.blade.php` | Botão "Enviar via WhatsApp" com link do portal |
| `resources/views/portal/acompanhar.blade.php` | Botão "Fale conosco" com mensagem de dúvida pré-preenchida |
