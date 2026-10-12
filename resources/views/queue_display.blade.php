<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMC OPD QMS - Lobby Monitor Display</title>
    <script src="https://jsdelivr.net"></script>
    @vite(['resources/js/app.js'])
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background-color: #0f172a; color: #f8fafc; }
        .header { background-color: #1e293b; padding: 20px; text-align: center; border-bottom: 4px solid #0284c7; }
        .container { display: flex; height: calc(100vh - 100px); }
        .dept-card { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; border-right: 2px solid #334155; padding: 20px; }
        .dept-card:last-child { border-right: none; }
        .vitals-zone { background-color: #0c4a6e; }
        .reg-zone { background-color: #064e3b; }
        .title { font-size: 2.5rem; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 10px; }
        .ticket-box { font-size: 8rem; font-weight: 900; background-color: #0f172a; padding: 10px 40px; border-radius: 20px; border: 4px solid #64748b; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.5); margin: 20px 0; color: #38bdf8; }
        .reg-zone .ticket-box { color: #4ade80; }
        .counter-label { font-size: 2rem; color: #cbd5e1; }
        .counter-number { font-size: 3.5rem; font-weight: bold; color: #ffffff; }
    </style>
</head>
<body>
    <div id="app">
        <div class="header">
            <h1>LA UNION MEDICAL CENTER</h1>
            <h2>Outpatient Department Queue Management Board</h2>
        </div>
        <div class="container">
            <!-- Vital Signs Panel -->
            <div class="dept-card vitals-zone">
                <div class="title">Vital Signs Booth</div>
                <div class="ticket-box">@{{ vitalsTicket }}</div>
                <div class="counter-label">Proceed to Station</div>
                <div class="counter-number">@{{ vitalsCounter }}</div>
            </div>

            <!-- Registration Panel -->
            <div class="dept-card reg-zone">
                <div class="title">Front Desk Registration</div>
                <div class="ticket-box">@{{ regTicket }}</div>
                <div class="counter-label">Proceed to Counter</div>
                <div class="counter-number">@{{ regCounter }}</div>
            </div>
        </div>
    </div>

    <script type="module">
        const { createApp, ref } = Vue;

        createApp({
            setup() {
                const vitalsTicket = ref('--');
                const vitalsCounter = ref('--');
                const regTicket = ref('--');
                const regCounter = ref('--');

                return { vitalsTicket, vitalsCounter, regTicket, regCounter };
            },
            mounted() {
                // Listen to the Pusher/Echo channel bridge for real-time broadcasts
                window.Echo.channel('lumc-opd-display')
                    .listen('.ticket.updated', (e) => {
                        const ticket = e.ticket;
                        if (ticket.current_step === 'vitals' && ticket.status === 'serving') {
                            this.vitalsTicket = ticket.ticket_number;
                            this.vitalsCounter = ticket.counter_number;
                        } else if (ticket.current_step === 'registration' && ticket.status === 'serving') {
                            this.regTicket = ticket.ticket_number;
                            this.regCounter = ticket.counter_number;
                        }
                    });
            }
        }).mount('#app');
    </script>
</body>
</html>
