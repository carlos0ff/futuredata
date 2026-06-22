# Configuração Evolution API na VPS

Guia completo para instalar e conectar a Evolution API (Go) com o FutureData na VPS.

---

## Requisitos

- VPS com Ubuntu 22.04+ (mín. 1 vCPU / 1 GB RAM)
- Docker + Docker Compose instalados
- Domínio apontando para a VPS (ex: `evo.seudominio.com.br`)
- Porta 8080 liberada no firewall da VPS

---

## 1. Instalar Docker (se necessário)

```bash
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER
newgrp docker
```

---

## 2. Subir a Evolution API com Docker Compose

Crie o diretório e o arquivo de configuração:

```bash
mkdir -p ~/evolution && cd ~/evolution
```

Crie o arquivo `docker-compose.yml`:

```yaml
version: "3.9"

services:
  evolution-api:
    image: atendai/evolution-api:latest
    container_name: evolution-api
    restart: unless-stopped
    ports:
      - "8080:8080"
    environment:
      SERVER_TYPE: http
      SERVER_PORT: 8080
      CORS_ORIGIN: "*"
      CORS_METHODS: "POST,GET,PUT,DELETE"
      CORS_CREDENTIALS: "true"

      # Chave global de acesso à API
      AUTHENTICATION_TYPE: apikey
      AUTHENTICATION_API_KEY: FUTUREDATA-KEY-2024

      # Banco de dados (SQLite por padrão — sem dependências extras)
      DATABASE_ENABLED: "false"

      # Webhooks — aponta para o FutureData
      WEBHOOK_GLOBAL_URL: https://seu-app.com/webhook/whatsapp
      WEBHOOK_GLOBAL_ENABLED: "true"
      WEBHOOK_GLOBAL_WEBHOOK_BY_EVENTS: "false"
      WEBHOOK_EVENTS_MESSAGES_UPSERT: "true"
      WEBHOOK_EVENTS_CONNECTION_UPDATE: "true"

      # Armazenamento local
      STORE_CLEANING_INTERVAL: "7200"
      STORE_MESSAGES: "true"
      STORE_MESSAGE_UP: "true"
      STORE_CONTACTS: "true"

      # Logs
      LOG_LEVEL: ERROR
      LOG_COLOR: "true"
      LOG_BAILEYS: error
    volumes:
      - evolution_data:/evolution/instances
    networks:
      - evolution

volumes:
  evolution_data:

networks:
  evolution:
    driver: bridge
```

> **Substitua** `FUTUREDATA-KEY-2024` por uma chave forte da sua escolha.
> **Substitua** `https://seu-app.com/webhook/whatsapp` pela URL real do FutureData.

Suba o container:

```bash
docker compose up -d
```

Verifique se subiu:

```bash
docker logs evolution-api -f
```

---

## 3. Criar a instância no WhatsApp

A instância representa o número que o FutureData vai usar.

```bash
# Substitua FUTUREDATA-KEY-2024 pela sua chave
curl -X POST http://localhost:8080/instance/create \
  -H "Content-Type: application/json" \
  -H "apikey: FUTUREDATA-KEY-2024" \
  -d '{
    "instanceName": "futuredata",
    "qrcode": true
  }'
```

---

## 4. Conectar o número `5581994821792`

### 4.1 Obter o QR Code

```bash
curl http://localhost:8080/instance/connect/futuredata \
  -H "apikey: FUTUREDATA-KEY-2024"
```

O campo `base64` da resposta é a imagem do QR code. Para visualizá-lo:

**Opção A — Via painel FutureData:**
1. Acesse `https://seu-app.com/app/whatsapp`
2. Clique em **Gerar QR Code**
3. Escaneie com o WhatsApp do número `+55 81 99482-1792`

**Opção B — Linha de comando:**
```bash
# Salva o QR como imagem PNG
curl -s http://localhost:8080/instance/connect/futuredata \
  -H "apikey: FUTUREDATA-KEY-2024" \
  | python3 -c "
import sys, json, base64
d = json.load(sys.stdin)
b64 = d.get('base64','').split(',')[-1]
open('/tmp/qr.png','wb').write(base64.b64decode(b64))
print('QR salvo em /tmp/qr.png')
"
```

### 4.2 Escanear com o celular

1. Abra o WhatsApp no número `+55 81 99482-1792`
2. Menu → **Dispositivos vinculados** → **Vincular dispositivo**
3. Escaneie o QR code

### 4.3 Verificar conexão

```bash
curl http://localhost:8080/instance/connectionState/futuredata \
  -H "apikey: FUTUREDATA-KEY-2024"
```

Resposta esperada: `{"instance":{"state":"open"}}`

---

## 5. Configurar no FutureData

### Via painel (recomendado):
1. Acesse `/app/whatsapp` com uma conta gerente/admin
2. Preencha:
   - **URL da Evolution API**: `http://IP-DA-VPS:8080` (ou domínio se configurou Nginx)
   - **API Key**: `FUTUREDATA-KEY-2024`
   - **Instância**: `futuredata`
3. Clique em **Salvar configurações**

### Via `.env` (manual):
```env
WHATSAPP_PROVIDER=evolution
WHATSAPP_EVOLUTION_URL=http://IP-DA-VPS:8080
WHATSAPP_EVOLUTION_KEY=FUTUREDATA-KEY-2024
WHATSAPP_EVOLUTION_INSTANCE=futuredata
WHATSAPP_WEBHOOK_SECRET=
WHATSAPP_BOT_ENABLED=true
```

Depois:
```bash
./vendor/bin/sail artisan config:clear
```

---

## 6. Configurar Nginx como proxy reverso (opcional mas recomendado)

Se quiser acessar a Evolution API por `evo.seudominio.com.br`:

```nginx
server {
    listen 80;
    server_name evo.seudominio.com.br;

    location / {
        proxy_pass         http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header   Upgrade $http_upgrade;
        proxy_set_header   Connection "upgrade";
        proxy_set_header   Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

Depois ative SSL com Certbot:

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d evo.seudominio.com.br
```

---

## 7. Webhook — registrar evento na instância

Após criar a instância, registre o webhook para garantir que os eventos sejam entregues:

```bash
curl -X POST http://localhost:8080/webhook/set/futuredata \
  -H "Content-Type: application/json" \
  -H "apikey: FUTUREDATA-KEY-2024" \
  -d '{
    "url": "https://seu-app.com/webhook/whatsapp",
    "webhook_by_events": false,
    "webhook_base64": false,
    "events": [
      "MESSAGES_UPSERT",
      "CONNECTION_UPDATE"
    ]
  }'
```

---

## 8. Testar o bot

Envie uma mensagem do número `+55 81 99482-1792` para o número conectado.

O bot deve responder com o menu de boas-vindas. Se o número não estiver cadastrado como cliente no FutureData, o bot pedirá o CPF ou código da OS.

Para testar via API (simular recebimento):

```bash
curl -X POST https://seu-app.com/webhook/whatsapp \
  -H "Content-Type: application/json" \
  -d '{
    "event": "messages.upsert",
    "data": {
      "key": {
        "fromMe": false,
        "remoteJid": "5581994821792@s.whatsapp.net"
      },
      "message": {
        "conversation": "oi"
      }
    }
  }'
```

---

## Solução de problemas

| Problema | Solução |
|---|---|
| Container não sobe | `docker logs evolution-api` — verifique a porta 8080 |
| QR code não aparece | Verifique se a instância foi criada corretamente |
| Webhook não recebe | Confirme que a URL do FutureData é acessível da VPS |
| Bot não responde | Verifique `WHATSAPP_BOT_ENABLED=true` no `.env` |
| "Unauthorized" no webhook | Ajuste `WHATSAPP_WEBHOOK_SECRET` para coincidir com a `apikey` da Evolution |

---

## Resumo rápido

```
Número: +55 81 99482-1792
Instância: futuredata
API Key: FUTUREDATA-KEY-2024  ← troque por algo seguro
Webhook: https://seu-app.com/webhook/whatsapp
Painel config: /app/whatsapp
```
