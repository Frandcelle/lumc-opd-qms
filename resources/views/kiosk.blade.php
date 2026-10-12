<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMC OPD QMS - Ticket Kiosk</title>
    <script src="https://jsdelivr.net"></script>
    <script src="https://jsdelivr.net"></script>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .kiosk-container { text-align: center; max-width: 600px; width: 100%; padding: 40px; background-color: #1e293b; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5); border: 2px solid #334155; }
        .logo-placeholder { font-size: 4rem; margin-bottom: 10px; }
        h1 { font-size: 2.5rem; color: #ffffff; margin: 0 0 10px 0; font-weight: 800; }
        h2 { font-size: 1.2rem; color: #94a3b8; margin: 0 0 40px 0; text-transform: uppercase; letter-spacing: 1px; }
        .instructions { font-size: 1.4rem; margin-bottom: 30px; color: #cbd5e1; }
        .btn-container { display: flex; flex-direction: column; gap: 20px; }
        .kiosk-btn { font-size: 1.8rem; font-weight: bold; padding: 25px; border: none; border-radius: 16px; cursor: pointer; color: #ffffff; transition: transform 0.1s, background-color 0.2s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3); }
        .kiosk-btn:active { transform: scale(0.98); }
        .btn-new { background-color: #0284c7; }
        .btn-new:hover { background-color: #0369a1; }
        .btn-old { background-color: #0d9488; }
        .btn-old:hover { background-color: #0f766e; }
        
        /* Modal Ticket Slip Styles */
        .ticket-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); display: flex; align-items: center; justify-content: center; }
        .ticket-slip { background: #ffffff; color: #000000; padding: 30px; border-radius: 8px; width: 300px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); text-align: center; font-family: 'Courier New', Courier, monospace; }
        .ticket-number { font-size: 3rem; font-weight: bold; margin: 20px 0; }
        .close-btn { margin-top: 20px; background: #000000; color: #ffffff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div id="app" class="kiosk-container">
        <div class="logo-placeholder">🏥</div>
        <h1>LUMC HOSPITAL</h1>
        <h2>Outpatient Department Triage</h2>
        
        <p class="instructions">Please select your classification to get your queue ticket number:</p>
        
        <div class="btn-container">
            <button class="kiosk-btn btn-new" @click="getTicket('new')">🆕 NEW PATIENT <br><small style="font-size:1rem; font-weight:normal;">(No hospital record yet)</small></button>
            <button class="kiosk-btn btn-old" @click="getTicket('old')">🔄 OLD PATIENT <br><small style="font-size:1rem; font-weight:normal;">(With existing hospital record)</small></button>
        </div>

        <!-- Pop-up Ticket Slip Simulation -->
        <div v-if="issuedTicket" class="ticket-modal">
            <div class="ticket-slip">
                <h3>LA UNION MEDICAL CENTER</h3>
                <p>Outpatient Department QMS</p>
                <hr style="border-top: 1px dashed #000;">
                <p>YOUR TRACKING NUMBER:</p>
                <div class="ticket-number">@{{ issuedTicket.ticket_number }}</div>
                <p>Classification: <strong>@{{ issuedTicket.patient_type.toUpperCase() }}</strong></p>
                <hr style="border-top: 1px dashed #000;">
                <p style="font-size: 0.8rem;">Please proceed immediately to the <br><strong>Vital Signs Assessment Area</strong>.</p>
                <p style="font-size: 0.7rem;">@{{ formatDateTime(issuedTicket.created_at) }}</p>
                <button class="close-btn" @click="clearTicket">DONE / PRINTED</button>
            </div>
        </div>
    </div>

    <script>
        const { createApp, ref } = Vue;

        createApp({
            setup() {
                const issuedTicket = ref(null);

                const getTicket = (type) => {
                    axios.post('/queue/issue', { patient_type: type })
                        .then(response => {
                            if (response.data.status === 'success') {
                                issuedTicket.value = response.data.data;
                            }
                        })
                        .catch(error => {
                            alert('Error connecting to QMS server.');
                            console.error(error);
                        });
                };

                const clearTicket = () => {
                    issuedTicket.value = null;
                };

                const formatDateTime = (dateStr) => {
                    const date = new Date(dateStr);
                    return date.toLocaleString();
                };

                return { getTicket, issuedTicket, clearTicket, formatDateTime };
            }
        }).mount('#app');
    </script>
</body>
</html>
