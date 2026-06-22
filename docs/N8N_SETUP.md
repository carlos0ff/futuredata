# Integração n8n — FutureData

## Arquitetura

```
FutureData ──► n8n webhook ──► processa evento ──► Evolution API ──► WhatsApp cliente
    │
    └── Eventos: os.criada | os.status_alterado | os.orcamento_respondido
```

```
WhatsApp ──► Evolution API ──► n8n (Agente IA) ──► responde automaticamente via WhatsApp
```

---

## Infraestrutura atual

| Serviço | URL |
|---------|-----|
| n8n | `http://2.24.205.178:32772` |
| Evolution API | `http://2.24.205.178:32774` |
| Instância WhatsApp | `carlos0ff.dev` |
| Evolution Manager | `http://2.24.205.178:32774/manager` |

**Credenciais n8n:** `carlosiilva66@gmail.com` / ver `.env`

---

## Workflow principal — Agente WhatsApp com IA

**ID:** `xBDFXlgalz5kMG36`
**Nome:** FutureData — Agente WhatsApp com IA
**Status:** Ativo

### Webhook de entrada
```
URL: http://2.24.205.178:32772/webhook/5acbbf43-ed70-4111-9049-b88bca8370a9
Método: POST
```

### Fluxo dos nodes

```
Recebe mensagem (Webhook)
    │
    ▼
Filtra fromMe e grupos (Filter)
    │  Bloqueia: fromMe=true, grupos @g.us, eventos ≠ messages.upsert
    ▼
Coleta (Set)
    │  Extrai: mensagem, nome, remoteJid
    ▼
Verifica tipo de arquivo (Switch)
    │  Rota: texto | audio | imagem | documento
    ▼
Edit Fields (Set)
    ▼
Merge
    ▼
Agente Future Data (AI Agent — LangChain)
    │  Modelo: Google Gemini 2.0 Flash
    │  Memória: Simple Memory (buffer)
    ▼
SEPARA MENSAGENS2 (Code)
    │  Divide resposta por \\ para múltiplas mensagens
    ▼
Loop Over Items1 (SplitInBatches)
    ▼
Envia mensagem para o WhatsApp (HTTP Request)
    │  POST http://2.24.205.178:32774/message/sendText/carlos0ff.dev
    ▼
DELAY 1 SEGUNDO (Wait)
    └──► Loop Over Items1
```

### Node: Envia mensagem para o WhatsApp

```
Método: POST
URL: http://2.24.205.178:32774/message/sendText/carlos0ff.dev
Headers:
  apikey: 6AF3EE269CF3-4175-B338-B6FD8EEA2F0C
Body:
  number: {{ $('Recebe mensagem').item.json.body.data.key.remoteJid }}
  text:   {{ $json.item }}
```

### Node: Filtro anti-loop

Condições (AND):
- `$json.body.data.key.fromMe` ≠ `true` — evita o bot responder a si mesmo
- `$json.body.data.key.remoteJid` não contém `@g.us` — ignora grupos
- `$json.body.event` = `messages.upsert` — só mensagens recebidas

---

## Webhook Evolution API → n8n

Configurado via API:

```bash
curl -X POST "http://2.24.205.178:32774/webhook/set/carlos0ff.dev" \
  -H "Content-Type: application/json" \
  -H "apikey: 6AF3EE269CF3-4175-B338-B6FD8EEA2F0C" \
  -d '{
    "webhook": {
      "url": "http://2.24.205.178:32772/webhook/5acbbf43-ed70-4111-9049-b88bca8370a9",
      "events": ["MESSAGES_UPSERT", "CONNECTION_UPDATE"],
      "webhookByEvents": false,
      "webhookBase64": false,
      "enabled": true
    }
  }'
```

---

## Modelo de IA

**Atual:** Google Gemini 2.0 Flash
**Credencial n8n:** `Google Gemini - FutureData` (tipo: `googlePalmApi`)
**Observações:**
- Tier gratuito: 15 req/min, ~1.500 req/dia
- Quota reseta diariamente à meia-noite
- Safety settings configurados como `BLOCK_NONE` para evitar bloqueios de conteúdo

Para trocar o modelo, editar o node `Google Gemini Chat Model` no workflow `xBDFXlgalz5kMG36`.

---

## System Prompt do Agente

O agente se identifica como assistente virtual da **Future Data Assistência Técnica** e responde perguntas sobre:
- Status e prazo de OS
- Orçamentos (aprovação/recusa)
- Acesso ao portal do cliente
- Transferência para técnico humano

Configurado diretamente no node `Agente Future Data` → campo `System Message`.

---

## Testar o webhook manualmente

```bash
curl -X POST "http://2.24.205.178:32772/webhook/5acbbf43-ed70-4111-9049-b88bca8370a9" \
  -H "Content-Type: application/json" \
  -d '{
    "event": "messages.upsert",
    "instance": "carlos0ff.dev",
    "data": {
      "key": {
        "remoteJid": "5581994821792@s.whatsapp.net",
        "fromMe": false,
        "id": "test-001"
      },
      "pushName": "Cliente Teste",
      "messageType": "conversation",
      "message": {"conversation": "Olá, qual o prazo de entrega?"}
    }
  }'
```

Resposta esperada: `{"message":"Workflow was started"}`

---

## Enviar mensagem diretamente (sem webhook)

```bash
curl -X POST "http://2.24.205.178:32774/message/sendText/carlos0ff.dev" \
  -H "Content-Type: application/json" \
  -H "apikey: 6AF3EE269CF3-4175-B338-B6FD8EEA2F0C" \
  -d '{
    "number": "5581994821792",
    "text": "Mensagem de teste da Future Data 🔧"
  }'
```

---

## Eventos disparados pelo FutureData → n8n

| Evento | Quando | Dados extras |
|--------|--------|-------------|
| `os.criada` | Nova OS aberta | — |
| `os.status_alterado` | Técnico muda status | `status_anterior`, `status_anterior_label` |
| `os.orcamento_respondido` | Cliente aprova/recusa | `resposta: aprovado\|recusado` |

### Estrutura do payload

```json
{
  "event": "os.criada",
  "timestamp": "2026-06-22T10:00:00-03:00",
  "os": {
    "id": 1,
    "numero": "OS202600001",
    "status": "entrada",
    "status_label": "Entrada",
    "url_portal": "https://seuapp.com/r/TOKEN"
  },
  "equipamento": {
    "tipo": "Notebook",
    "marca": "Dell",
    "modelo": "Inspiron 15",
    "nome": "Notebook Dell Inspiron 15"
  },
  "cliente": {
    "nome": "João Silva",
    "telefone": "11999999999",
    "email": "joao@email.com"
  }
}
```

### Configurar URL no .env

```env
N8N_WEBHOOK_URL=http://2.24.205.178:32772/webhook/futuredata
```
