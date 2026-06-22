# Integração n8n — FutureData

## Arquitetura

```
FutureData ──► n8n webhook ──► processa evento ──► Evolution API ──► WhatsApp cliente
    │
    └── Eventos: os.criada | os.status_alterado | os.orcamento_respondido
```

```
WhatsApp ──► Evolution API ──► n8n ──► FutureData webhook ──► salva + bot responde
```

---

## 1. Configurar n8n na VPS

### Porta padrão
n8n roda em `http://2.24.205.178:5678`

### Abrir no firewall (se necessário)
```bash
sudo ufw allow 5678
```

---

## 2. Criar o Webhook no n8n (recebe eventos do FutureData)

### 2.1 Criar workflow "FutureData Events"

1. Acesse `http://2.24.205.178:5678`
2. **New Workflow** → nome: `FutureData Events`
3. Adicione node **Webhook**:
   - **HTTP Method**: POST
   - **Path**: `futuredata`
   - Anote a URL gerada: `http://2.24.205.178:5678/webhook/futuredata`

4. Adicione node **Switch** (logo após o Webhook):
   - **Mode**: Rules
   - **Value**: `{{ $json.event }}`
   - Regras:
     - `os.criada`
     - `os.status_alterado`
     - `os.orcamento_respondido`

### 2.2 Ramo: `os.criada` → WhatsApp boas-vindas

Adicione node **HTTP Request**:
```
Method: POST
URL: http://2.24.205.178:32774/message/sendText/carlos0ff.dev
Headers:
  apikey: 6AF3EE269CF3-4175-B338-B6FD8EEA2F0C
  Content-Type: application/json
Body (JSON):
{
  "number": "55{{ $json.cliente.telefone }}",
  "text": "Olá, {{ $json.cliente.nome }}! 👋\n\nSeu equipamento foi recebido com sucesso.\n\n📄 OS: {{ $json.os.numero }}\n📱 Equipamento: {{ $json.equipamento.nome }}\n\nAcompanhe pelo portal do cliente:\n{{ $json.os.url_portal }}\n\nFuture Data Assistência Técnica 🔧"
}
```

### 2.3 Ramo: `os.status_alterado` → notifica mudança

Adicione node **HTTP Request**:
```json
{
  "number": "55{{ $json.cliente.telefone }}",
  "text": "📋 *{{ $json.os.numero }}* — Status atualizado!\n\nDe: {{ $json.status_anterior_label }}\nPara: *{{ $json.os.status_label }}*\n\nDetalhes: {{ $json.os.url_portal }}"
}
```

### 2.4 Ramo: `os.orcamento_respondido` → confirma para a equipe

Adicione node **HTTP Request** (envia para número da loja/gerente):
```json
{
  "number": "555581994821792",
  "text": "💰 Orçamento *{{ $json.resposta | upper }}*\nOS: {{ $json.os.numero }}\nCliente: {{ $json.cliente.nome }}\nEquipamento: {{ $json.equipamento.nome }}"
}
```

5. **Activate** o workflow (toggle no canto superior direito)

---

## 3. Configurar Evolution → n8n (mensagens WhatsApp entram no n8n)

No painel Evolution Manager (`2.24.205.178:32774/manager`):

1. Vá em **Integrações → n8n**
2. Preencha:
   - **n8n API URL**: `http://2.24.205.178:5678`
   - **Webhook URL**: `http://2.24.205.178:5678/webhook/whatsapp-in`
3. Salve

### Criar workflow "WhatsApp Recebido" no n8n

1. **New Workflow** → nome: `WhatsApp Recebido`
2. Node **Webhook** → Path: `whatsapp-in`
3. Node **IF** → condição: `{{ $json.event }}` equals `messages.upsert`
4. Node **HTTP Request** → repassa para o FutureData:
```
Method: POST
URL: http://SEU-APP/webhook/whatsapp
Body: {{ $json }}
```

> Isso permite inserir lógica no n8n antes de chegar ao FutureData (ex: filtros, logs, CRM externo).

---

## 4. Testar o fluxo

### Testar evento os.criada
```bash
curl -X POST http://2.24.205.178:5678/webhook/futuredata \
  -H "Content-Type: application/json" \
  -d '{
    "event": "os.criada",
    "os": { "numero": "OS202600001", "status_label": "Entrada", "url_portal": "https://exemplo.com/r/abc" },
    "equipamento": { "nome": "Notebook Dell Inspiron" },
    "cliente": { "nome": "João Silva", "telefone": "11999999999" },
    "timestamp": "2026-06-22T10:00:00"
  }'
```

### Confirmar no FutureData (.env)
```env
N8N_WEBHOOK_URL=http://2.24.205.178:5678/webhook/futuredata
```

---

## Eventos disparados pelo FutureData

| Evento | Quando | Dados extras |
|--------|--------|-------------|
| `os.criada` | Nova OS aberta | — |
| `os.status_alterado` | Técnico muda status | `status_anterior`, `status_anterior_label` |
| `os.orcamento_respondido` | Cliente aprova/recusa | `resposta: aprovado\|recusado` |

### Estrutura do payload (todos os eventos)
```json
{
  "event": "os.criada",
  "timestamp": "2026-06-22T10:00:00-03:00",
  "os": {
    "id": 1,
    "numero": "OS202600001",
    "status": "entrada",
    "status_label": "Entrada",
    "diagnostico": null,
    "status_orcamento": null,
    "previsao_entrega": null,
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
