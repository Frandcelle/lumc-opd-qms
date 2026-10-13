
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>LUMC | OPD Registration Queue</title>

<style>
:root {
    --navy: #101d42;
    --blue: #21469b;
    --red: #d92332;
    --gold: #f2c94c;
    --bg: #eef3f9;
    --muted: #64748b;
    --border: #dce4ef;
}
* { box-sizing: border-box; }
body {
    margin: 0;
    min-height: 100vh;
    padding: 24px 14px;
    font-family: "Segoe UI", Arial, sans-serif;
    color: var(--navy);
    background: var(--bg);
}
.lumc-header {
    max-width: 1000px;
    margin: 0 auto 22px;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    background: #fff;
    border: 1px solid var(--border);
    border-bottom: 3px solid var(--red);
    border-radius: 14px;
    box-shadow: 0 5px 20px #101d420b;
}
.lumc-main-logo { width: 54px; height: 54px; object-fit: contain; }
.lumc-brand { flex: 1; min-width: 0; }
.lumc-brand strong {
    display: block;
    font-size: clamp(14px, 2.5vw, 21px);
    font-weight: 900;
}
.lumc-brand span {
    display: block;
    margin-top: 5px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.2px;
}
.partner-logos { display: flex; align-items: center; gap: 10px; }
.partner-logos img { width: 32px; height: 32px; object-fit: contain; }

main {
    width: 100%;
    max-width: 700px;
    margin: 0 auto;
    padding: clamp(22px, 5vw, 40px);
    background: #fff;
    border: 1px solid var(--border);
    border-top: 5px solid var(--red);
    border-radius: 20px;
    box-shadow: 0 18px 45px #101d4210;
}
.eyebrow {
    text-align: center;
    color: var(--red);
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 2px;
}
h1 {
    margin: 8px 0;
    text-align: center;
    color: var(--navy);
    font-size: clamp(26px, 4vw, 34px);
    font-weight: 900;
}
.subtitle {
    margin: 0 auto 24px;
    text-align: center;
    color: var(--muted);
    line-height: 1.6;
    font-size: 14px;
}
label {
    display: block;
    margin: 14px 0 7px;
    font-size: 14px;
    font-weight: 750;
}
input {
    display: block;
    width: 100%;
    min-height: 48px;
    padding: 12px 14px;
    border: 1px solid #c7d4e5;
    border-radius: 9px;
    color: var(--navy);
    background: #fff;
    font: inherit;
    font-size: 16px;
    outline: none;
}
input:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px #21469b1a;
}
button {
    min-height: 48px;
    padding: 13px 16px;
    border: 0;
    border-radius: 10px;
    font: inherit;
    font-size: 14px;
    font-weight: 850;
    cursor: pointer;
    transition: filter .15s, transform .15s;
}
button:hover { filter: brightness(1.05); }
button:disabled { opacity: .6; cursor: wait; }

.next {
    width: 100%;
    margin-top: 18px;
    color: #fff;
    background: var(--blue);
    box-shadow: 0 5px 14px #21469b26;
}
#work {
    margin-top: 22px;
    padding: clamp(16px, 4vw, 26px);
    background: #f8faff;
    border: 1px solid var(--border);
    border-radius: 14px;
}
#work[hidden], #next[hidden] { display: none !important; }
.now-serving {
    margin: 0;
    text-align: center;
    color: var(--muted);
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 2px;
}
.number {
    margin: 14px 0 20px;
    padding: 22px 8px;
    border: 1px solid #d7e5fb;
    border-radius: 12px;
    background: #edf4ff;
    color: var(--blue);
    text-align: center;
    font-size: clamp(27px, 6.2vw, 46px);
    line-height: 1.2;
    font-weight: 950;
    letter-spacing: -1px;
    overflow-wrap: anywhere;
}
.instruction {
    margin: 0 0 20px;
    text-align: center;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.6;
}
.complete {
    width: 100%;
    color: #fff;
    background: var(--red);
}
.actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 12px;
}
.actions button { width: 100%; }
.recall { color: var(--navy); background: #e7edf6; }
.skip { color: #8a4b00; background: #fff0d8; }
#status {
    min-height: 24px;
    margin: 18px 0 0;
    text-align: center;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.5;
}
@media(max-width:520px) {
    body { padding: 12px; }
    .lumc-header { padding: 11px; gap: 9px; }
    .lumc-main-logo { width: 42px; height: 42px; }
    .lumc-brand span { font-size: 8px; letter-spacing: .5px; }
    .partner-logos { gap: 4px; }
    .partner-logos img { width: 23px; height: 23px; }
    main { padding: 23px 16px; border-radius: 15px; }
    .actions { grid-template-columns: 1fr; }
}
</style>
</head>

<body>
<header class="lumc-header">
    <img class="lumc-main-logo" src="{{ asset('images/LUMCLOGO.png') }}" alt="LUMC Logo">
    <div class="lumc-brand">
        <strong>LA UNION MEDICAL CENTER</strong>
        <span>OUTPATIENT DEPARTMENT · REGISTRATION</span>
    </div>
    <div class="partner-logos">
        <img src="{{ asset('images/provinceofelyu.png') }}" alt="Province of La Union">
        <img src="{{ asset('images/LaUnionAgkaysa.png') }}" alt="La Union Agkaysa">
        <img src="{{ asset('images/BagongPilipinas.png') }}" alt="Bagong Pilipinas">
    </div>
</header>

<main>
    <div class="eyebrow">PATIENT QUEUE MANAGEMENT</div>
    <h1>OPD CLERK</h1>
    <p class="subtitle">Registration and patient information encoding.</p>

    <label for="counter">Counter number</label>
    <input id="counter" type="number" min="1" value="1">

    <button id="next" class="next" onclick="nextPatient()">CALL NEXT NUMBER</button>

    <section id="work" hidden>
        <p class="now-serving">NOW SERVING</p>
        <div class="number" id="ticket">---</div>

        <p class="instruction">
            Verify the patient's paper form and encode the required information
            in the hospital system.
        </p>

        <button class="complete" onclick="completePatient()">COMPLETE REGISTRATION</button>

        <div class="actions">
            <button class="recall" onclick="recallPatient()">RECALL NUMBER</button>
            <button class="skip" onclick="skipPatient()">SKIP / ABSENT</button>
        </div>
    </section>

    <p id="status" role="status" aria-live="polite"></p>
</main>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
let patient = null;

async function post(url) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf
        }
    });

    const data = await response.json();

    if (!response.ok || data.status === 'error') {
        throw new Error(data.message || 'The request failed.');
    }
    return data;
}

async function nextPatient() {
    const counter = document.getElementById('counter').value;

    if (!counter || Number(counter) < 1) {
        showMessage('Enter a valid counter number.');
        return;
    }

    try {
        const data = await post(
            '/queue/registration/next/' + encodeURIComponent(counter)
        );

        if (data.status !== 'success') {
            showMessage(data.message || 'No patients are waiting.');
            return;
        }

        patient = data.ticket;
        document.getElementById('ticket').textContent = patient.ticket_number;
        document.getElementById('work').hidden = false;
        document.getElementById('next').hidden = true;
        showMessage('');
    } catch (error) {
        showMessage(error.message);
    }
}

async function completePatient() {
    if (!patient) return;

    try {
        const data = await post('/queue/registration/complete/' + patient.id);
        resetPatient();
        showMessage(data.message || 'Registration completed.');
    } catch (error) {
        showMessage(error.message);
    }
}

function recallPatient() {
    if (!patient) return;

    showMessage('Recalling ' + patient.ticket_number + ' at OPD Registration.');
    speakNumber(patient.ticket_number);
}

async function skipPatient() {
    if (!patient || !confirm(
        'Mark ' + patient.ticket_number + ' as absent/skipped?'
    )) return;

    try {
        const data = await post('/queue/registration/skip/' + patient.id);
        resetPatient();
        showMessage(data.message || 'Ticket marked as skipped.');
    } catch (error) {
        showMessage(error.message);
    }
}

function speakNumber(number) {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(
            new SpeechSynthesisUtterance(
                'Queue number ' + number + ', please proceed to OPD Registration.'
            )
        );
    }
}

function resetPatient() {
    patient = null;
    document.getElementById('work').hidden = true;
    document.getElementById('next').hidden = false;
}

function showMessage(message) {
    document.getElementById('status').textContent = message;
}
</script>
</body>
</html>
