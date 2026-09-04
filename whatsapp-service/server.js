const express = require('express');
const cors = require('cors');
const { Client, LocalAuth, MessageMedia } = require('whatsapp-web.js');
const qrcode = require('qrcode');

const app = express();
app.use(cors());
app.use(express.json());

// Store clients and their statuses
const clients = new Map();
const qrDataUrls = new Map();
const readyStatuses = new Map();

// Helper to get or create a client
const getClient = (tenantId) => {
    if (clients.has(tenantId)) {
        return clients.get(tenantId);
    }

    const client = new Client({
        authStrategy: new LocalAuth({ clientId: `tenant_${tenantId}` }),
        puppeteer: {
            executablePath: 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
            headless: 'new',
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-accelerated-2d-canvas',
                '--no-first-run',
                '--no-zygote',
                '--disable-gpu'
            ]
        }
    });

    readyStatuses.set(tenantId, false);
    
    client.on('qr', async (qr) => {
        try {
            const qrDataUrl = await qrcode.toDataURL(qr);
            qrDataUrls.set(tenantId, qrDataUrl);
            readyStatuses.set(tenantId, false);
            console.log(`[Tenant ${tenantId}] QR Code generated. Waiting for scan...`);
        } catch (err) {
            console.error(`[Tenant ${tenantId}] Error generating QR code`, err);
        }
    });

    client.on('ready', () => {
        readyStatuses.set(tenantId, true);
        qrDataUrls.delete(tenantId);
        console.log(`[Tenant ${tenantId}] WhatsApp Client is ready!`);
    });

    client.on('authenticated', () => {
        console.log(`[Tenant ${tenantId}] WhatsApp Authenticated!`);
    });

    client.on('auth_failure', msg => {
        console.error(`[Tenant ${tenantId}] WhatsApp Authentication failure`, msg);
        readyStatuses.set(tenantId, false);
    });

    client.on('disconnected', (reason) => {
        console.log(`[Tenant ${tenantId}] WhatsApp Client was disconnected`, reason);
        readyStatuses.set(tenantId, false);
        qrDataUrls.delete(tenantId);
        
        // Attempt to restart client
        client.initialize().catch(console.error);
    });

    client.initialize().catch(console.error);
    clients.set(tenantId, client);
    
    return client;
};

// Middleware to extract tenant_id
const requireTenant = (req, res, next) => {
    const tenantId = req.headers['x-tenant-id'] || 
                     (req.query && req.query.tenant_id) || 
                     (req.body && req.body.tenant_id);
                     
    if (!tenantId) {
        return res.status(400).json({ error: 'Tenant ID is required' });
    }
    req.tenantId = tenantId;
    next();
};

app.use(requireTenant);

// API Endpoints
app.get('/api/status', (req, res) => {
    const tenantId = req.tenantId;
    
    if (!clients.has(tenantId)) {
        getClient(tenantId);
        return res.json({ status: 'initializing' });
    }

    const isReady = readyStatuses.get(tenantId);
    const qrDataUrl = qrDataUrls.get(tenantId);

    if (isReady) {
        res.json({ status: 'connected' });
    } else if (qrDataUrl) {
        res.json({ status: 'qr_ready', qr: qrDataUrl });
    } else {
        res.json({ status: 'disconnected' });
    }
});

app.post('/api/pair', async (req, res) => {
    const tenantId = req.tenantId;
    let client = clients.get(tenantId);

    if (!client) {
        client = getClient(tenantId);
    }

    const { phoneNumber } = req.body;
    if (!phoneNumber) {
        return res.status(400).json({ error: 'Phone number is required' });
    }

    try {
        // Must wait for the 'qr' event to have fired at least once before requesting a pairing code
        const code = await client.requestPairingCode(phoneNumber.replace('+', ''));
        res.json({ success: true, code });
    } catch (error) {
        console.error(`[Tenant ${tenantId}] Error requesting pairing code:`, error);
        res.status(500).json({ error: 'Failed to request pairing code' });
    }
});

app.post('/api/send', async (req, res) => {
    const tenantId = req.tenantId;
    const isReady = readyStatuses.get(tenantId);
    const client = clients.get(tenantId);

    if (!isReady || !client) {
        return res.status(400).json({ error: 'WhatsApp client is not ready' });
    }

    const { number, message, mediaUrl } = req.body;

    if (!number) {
        return res.status(400).json({ error: 'Phone number is required' });
    }

    try {
        const formattedNumber = number.replace('+', '') + '@c.us';

        if (mediaUrl) {
            const media = await MessageMedia.fromUrl(mediaUrl);
            await client.sendMessage(formattedNumber, media, { caption: message });
        } else {
            await client.sendMessage(formattedNumber, message);
        }

        res.json({ success: true, message: 'Message sent successfully' });
    } catch (error) {
        console.error(`[Tenant ${tenantId}] Error sending message:`, error);
        res.status(500).json({ error: 'Failed to send message', details: error.message });
    }
});

app.post('/api/logout', async (req, res) => {
    const tenantId = req.tenantId;
    const client = clients.get(tenantId);
    
    if (!client) {
        return res.json({ success: true, message: 'Already logged out' });
    }

    try {
        await client.logout();
        readyStatuses.set(tenantId, false);
        qrDataUrls.delete(tenantId);
        client.initialize(); // Reinitialize to get new QR
        res.json({ success: true, message: 'Logged out successfully' });
    } catch (error) {
        console.error(`[Tenant ${tenantId}] Failed to logout`, error);
        res.status(500).json({ error: 'Failed to logout' });
    }
});

process.on('uncaughtException', (err) => {
    console.error('Uncaught Exception:', err);
});

process.on('unhandledRejection', (reason, promise) => {
    console.error('Unhandled Rejection at:', promise, 'reason:', reason);
});

const PORT = 3001;
app.listen(PORT, () => {
    console.log(`Multi-Tenant WhatsApp microservice running on http://localhost:${PORT}`);
});
