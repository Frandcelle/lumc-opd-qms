<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LUMC | OPD Queue Display</title>

    <style>
        :root {
            --navy: #101d42;
            --blue: #203f91;
            --red: #d92332;
            --gold: #f2c94c;
            --white: #fff;
            --muted: #d4deef;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--navy);
            color: var(--white);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        /* Hospital branding */
        .topbar {
            min-height: 82px;
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px clamp(16px, 3vw, 38px);
            background: var(--white);
            color: var(--navy);
            border-bottom: 4px solid var(--red);
        }

        .logo {
            width: 50px;
            height: 50px;
            flex-shrink: 0;
            object-fit: contain;
        }

        .brand {
            font-size: clamp(14px, 2vw, 20px);
            font-weight: 900;
        }

        .subtitle {
            margin-top: 4px;
            color: #697790;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .logos {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: auto;
        }

        .logos img {
            width: 34px;
            height: 34px;
            object-fit: contain;
        }

        /* Page heading */
        .heading {
            padding: 24px 12px 12px;
            text-align: center;
        }

        .heading h1 {
            margin: 0;
            font-size: clamp(22px, 3vw, 36px);
            font-weight: 900;
        }

        .heading p {
            margin: 8px 0;
            color: var(--muted);
            font-size: 14px;
        }

        .gold-line {
            width: 72px;
            height: 4px;
            margin: 14px auto;
            border-radius: 4px;
            background: var(--gold);
        }

        /* Two station panels */
        .panels {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            width: 100%;
            max-width: 1500px;
            margin: 12px auto 0;
            padding: 12px clamp(14px, 3vw, 40px) 28px;
        }

        .panel {
            min-width: 0;
            overflow: hidden;
            border: 1px solid #354b77;
            border-radius: 18px;
            background: #172b53;
            text-align: center;
        }

        .panel-head {
            padding: 18px 12px;
            border-bottom: 1px solid #3a4d7c;
            background: #1c3670;
        }

        .panel.reg .panel-head {
            background: #292c57;
            border-bottom-color: #7d394d;
        }

        .station-label {
            font-size: clamp(16px, 2vw, 24px);
            font-weight: 800;
            letter-spacing: .8px;
        }

        .station-sub {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .panel-body {
            padding: clamp(22px, 4vw, 48px) 14px;
        }

        .call-label {
            color: #c5d2eb;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .number {
            display: block;
            width: 100%;
            margin: 18px auto 20px;
            color: var(--white);
            font-size: clamp(24px, 4.1vw, 68px);
            font-weight: 900;
            letter-spacing: -1.5px;
            line-height: 1.25;
            font-variant-numeric: tabular-nums;
            overflow-wrap: anywhere;
        }

        .panel.vitals .number {
            color: #9ed0ff;
        }

        .panel.reg .number {
            color: #ffb0b8;
        }

        .counter {
            display: inline-block;
            padding: 9px 18px;
            border: 1px solid #50658e;
            border-radius: 30px;
            background: var(--navy);
            color: var(--gold);
            font-size: 13px;
            font-weight: 800;
        }

        .wait {
            padding: 8px 14px 24px;
            color: var(--muted);
            font-size: 13px;
            letter-spacing: .7px;
            text-align: center;
        }

        .connection {
            min-height: 18px;
            padding: 0 12px 12px;
            color: #b8c8e5;
            font-size: 11px;
            text-align: center;
        }

        @media (max-width: 650px) {
            .panels {
                grid-template-columns: minmax(0, 1fr);
            }

            .panel-body {
                padding: 24px 12px;
            }

            .number {
                font-size: clamp(28px, 8vw, 46px);
            }

            .logos {
                gap: 5px;
            }

            .logos img {
                width: 25px;
                height: 25px;
            }

            .logo {
                width: 43px;
                height: 43px;
            }
        }
    </style>
</head>

<body>
    <header class="topbar">
        <img
            class="logo"
            src="{{ asset('images/LUMCLOGO.png') }}"
            alt="LUMC Logo"
        >

        <div>
            <div class="brand">LA UNION MEDICAL CENTER</div>
            <div class="subtitle">OUTPATIENT DEPARTMENT</div>
        </div>

        <div class="logos">
            <img src="{{ asset('images/provinceofelyu.png') }}" alt="Province of La Union">
            <img src="{{ asset('images/LaUnionAgkaysa.png') }}" alt="La Union Agkaysa">
            <img src="{{ asset('images/BagongPilipinas.png') }}" alt="Bagong Pilipinas">
        </div>
    </header>

    <section class="heading">
        <h1>OPD QUEUE DISPLAY</h1>
        <p>Please proceed when your number is called.</p>
        <div class="gold-line"></div>
    </section>

    <main class="panels">
        <section class="panel vitals">
            <div class="panel-head">
                <div class="station-label">VITAL SIGNS</div>
                <div class="station-sub">VITAL SIGNS ASSESSMENT AREA</div>
            </div>

            <div class="panel-body">
                <div class="call-label">NOW SERVING</div>
                <div class="number" id="vitals">---</div>
                <div class="counter" id="vitalsCounter">STATION 1</div>
            </div>
        </section>

        <section class="panel reg">
            <div class="panel-head">
                <div class="station-label">OPD REGISTRATION</div>
                <div class="station-sub">OPD CLERK</div>
            </div>

            <div class="panel-body">
                <div class="call-label">NOW SERVING</div>
                <div class="number" id="registration">---</div>
                <div class="counter" id="registrationCounter">COUNTER 1</div>
            </div>
        </section>
    </main>

    <footer>
        <div class="wait">PLEASE WAIT FOR YOUR NUMBER TO BE CALLED</div>
        <div class="connection" id="connectionStatus" aria-live="polite"></div>
    </footer>

    <script>
        const displayUrl = @json(url('/queue/display-data'));

        async function refreshQueue() {
            try {
                const response = await fetch(displayUrl, {
                    headers: {
                        Accept: 'application/json'
                    },
                    cache: 'no-store'
                });

                if (!response.ok) {
                    throw new Error('Queue data unavailable');
                }

                const data = await response.json();

                const vitals = data.vitals?.[0] ?? null;
                const registration = data.registration?.[0] ?? null;

                document.getElementById('vitals').textContent =
                    vitals?.ticket_number ?? '---';

                document.getElementById('vitalsCounter').textContent =
                    vitals?.counter_number
                        ? 'STATION ' + vitals.counter_number
                        : 'STATION 1';

                document.getElementById('registration').textContent =
                    registration?.ticket_number ?? '---';

                document.getElementById('registrationCounter').textContent =
                    registration?.counter_number
                        ? 'COUNTER ' + registration.counter_number
                        : 'COUNTER 1';

                document.getElementById('connectionStatus').textContent = '';
            } catch (error) {
                // Keep the last successfully displayed numbers.
                document.getElementById('connectionStatus').textContent =
                    'Reconnecting to queue system...';
            }
        }

        refreshQueue();
        setInterval(refreshQueue, 3000);
    </script>
</body>
</html>