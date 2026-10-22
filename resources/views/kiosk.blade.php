
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>LUMC | OPD Queue</title>

    <style>
        :root {
            --navy: #101d42;
            --blue: #203f91;
            --red: #d92332;
            --gold: #f2c94c;
            --white: #ffffff;
            --muted: #64738a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--navy);
            background: linear-gradient(
                135deg,
                #101d42 0%,
                #203f91 65%,
                #172654 100%
            );
        }

        /* Hospital header */
        .topbar {
            min-height: 82px;
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px clamp(16px, 4vw, 48px);
            background: var(--white);
            color: var(--navy);
            box-shadow: 0 3px 14px #0002;
        }

        .logo {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            object-fit: contain;
        }

        .brand {
            font-size: clamp(13px, 2vw, 19px);
            font-weight: 900;
            line-height: 1.3;
        }

        .tagline {
            margin-top: 3px;
            color: #78849a;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .govlogos {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: auto;
        }

        .govlogos img {
            width: 35px;
            height: 35px;
            object-fit: contain;
        }

        /* Main kiosk layout */
        main {
            min-height: calc(100vh - 82px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 34px 18px;
        }

        .card {
            width: min(100%, 600px);
            padding: clamp(24px, 5vw, 46px);
            background: var(--white);
            color: var(--navy);
            border-top: 5px solid var(--gold);
            border-radius: 20px;
            box-shadow: 0 24px 70px #06112e55;
            text-align: center;
        }

        .eyebrow {
            color: var(--blue);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        h1 {
            margin: 12px 0 10px;
            font-size: clamp(28px, 5vw, 42px);
            line-height: 1.15;
        }

        .intro {
            max-width: 410px;
            margin: 0 auto 24px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.6;
        }

        .actions {
            display: grid;
            gap: 12px;
        }

        .actions[hidden],
        .ticket[hidden] {
            display: none !important;
        }

        button {
            width: 100%;
            min-height: 62px;
            padding: 14px;
            border: 0;
            border-radius: 11px;
            color: var(--white);
            font-family: inherit;
            font-size: 17px;
            font-weight: 800;
            cursor: pointer;
            transition: transform .18s, filter .18s;
        }

        button:hover {
            transform: translateY(-2px);
            filter: brightness(1.06);
        }

        button:focus-visible {
            outline: 3px solid var(--gold);
            outline-offset: 3px;
        }

        button:disabled {
            opacity: .6;
            cursor: wait;
            transform: none;
        }

        .newbtn {
            background: var(--red);
            box-shadow: 0 7px 18px #d923322b;
        }

        .oldbtn {
            background: var(--blue);
        }

        .small {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            font-weight: 500;
            opacity: .9;
        }

        /* Issued ticket */
        .ticket {
            margin-top: 22px;
            padding: 24px 14px;
            background: #f5f7fc;
            border: 1px dashed #b7c4dc;
            border-radius: 14px;
        }

        .ticket-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .ticket-number {
            margin: 18px 0;
            color: var(--blue);
            font-size: clamp(32px, 7vw, 52px);
            font-weight: 900;
            overflow-wrap: anywhere;
        }

        .ticket-note {
            color: var(--muted);
            line-height: 1.5;
        }

        .done {
            margin-top: 12px;
            background: var(--navy);
        }

        .status {
            min-height: 20px;
            margin: 12px 0 0;
            color: #b42332;
            font-size: 13px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .footer {
            margin-top: 24px;
            color: #080b0f;
            font-size: 11px;
            letter-spacing: .8px;
        }

        @media (max-width: 520px) {
            .topbar {
                gap: 9px;
            }

            .logo {
                width: 44px;
                height: 44px;
            }

            .govlogos {
                gap: 5px;
            }

            .govlogos img {
                width: 26px;
                height: 26px;
            }

            .card {
                border-radius: 16px;
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
            <div class="tagline">AGOO, LA UNION · AGKAYSA!</div>
        </div>

        <div class="govlogos">
            <img src="{{ asset('images/provinceofelyu.png') }}" alt="Province of La Union">
            <img src="{{ asset('images/LaUnionAgkaysa.png') }}" alt="La Union Agkaysa">
            <img src="{{ asset('images/BagongPilipinas.png') }}" alt="Bagong Pilipinas">
        </div>
    </header>

    <main>
        <section class="card">
            <div class="eyebrow">OUTPATIENT DEPARTMENT</div>

            <h1>Get Your Queue Number</h1>

            <p class="intro">
                Kumuha ng number, sagutan ang triage form, at maghintay
                na tawagin sa Vital Signs.
            </p>

            <div class="actions" id="actions">
                <button class="newbtn" type="button" onclick="getTicket('new')">
                    GET QUEUE NUMBER
                    <span class="small">New or returning OPD visit</span>
                </button>

                <button class="oldbtn" type="button" onclick="getTicket('old')">
                    EXISTING PATIENT
                    <span class="small">May existing hospital record</span>
                </button>
            </div>

            <div class="ticket" id="ticket" hidden>
                <div class="ticket-label">YOUR QUEUE NUMBER</div>

                <div class="ticket-number" id="number">OPD-000</div>

                <p class="ticket-note">
                    Please fill out your paper triage form and proceed
                    to the Vital Signs waiting area.
                </p>

                <button class="done" type="button" onclick="resetKiosk()">
                    DONE
                </button>
            </div>

            <p class="status" id="status" role="status" aria-live="polite"></p>

            <div class="footer">
                PLEASE WAIT FOR YOUR NUMBER TO BE CALLED
            </div>
        </section>
    </main>

    <script>
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        ).content;

        let busy = false;

        async function getTicket(patientType) {
            if (busy) return;

            busy = true;

            const buttons = document.querySelectorAll("#actions button");
            const status = document.getElementById("status");

            buttons.forEach(button => button.disabled = true);
            status.textContent = "Generating queue number...";

            try {
                const response = await fetch(
                    "{{ url('/queue/issue') }}",
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": csrfToken
                        },
                        body: JSON.stringify({
                            patient_type: patientType
                        })
                    }
                );

                const data = await response.json();

                if (!response.ok || data.status !== "success") {
                    throw new Error(
                        data.message ||
                        "Unable to issue a number. Please ask OPD staff for help."
                    );
                }

                document.getElementById("number").textContent =
                    data.data.ticket_number;

                document.getElementById("actions").hidden = true;
                document.getElementById("ticket").hidden = false;

                status.textContent = "";
            } catch (error) {
                status.textContent =
                    error.message ||
                    "Connection error. Please ask OPD staff for help.";
            } finally {
                busy = false;
                buttons.forEach(button => button.disabled = false);
            }
        }

        function resetKiosk() {
            document.getElementById("ticket").hidden = true;
            document.getElementById("actions").hidden = false;
            document.getElementById("status").textContent = "";
        }
    </script>
</body>
</html>
