
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>LUMC | Initial Vital Signs</title>

<style>
:root {
    --navy:#101d42;
    --blue:#21469b;
    --red:#d92332;
    --gold:#f2c94c;
    --teal:#087f8c;
    --bg:#eef3f9;
    --muted:#64748b;
    --border:#dce4ef;
}
*{box-sizing:border-box}
body{
    margin:0;min-height:100vh;padding:24px 14px;
    font-family:"Segoe UI",Arial,sans-serif;color:var(--navy);
    background:var(--bg);
}
.lumc-header{
    max-width:1000px;margin:0 auto 22px;padding:14px 20px;
    display:flex;align-items:center;gap:14px;background:#fff;
    border:1px solid var(--border);border-bottom:3px solid var(--red);
    border-radius:14px;box-shadow:0 5px 20px #101d420b;
}
.lumc-main-logo{width:54px;height:54px;object-fit:contain}
.lumc-brand{flex:1;min-width:0}
.lumc-brand strong{display:block;font-size:clamp(14px,2.5vw,21px);font-weight:900}
.lumc-brand span{
    display:block;margin-top:5px;color:var(--muted);
    font-size:10px;font-weight:800;letter-spacing:1.2px
}
.partner-logos{display:flex;align-items:center;gap:10px}
.partner-logos img{width:32px;height:32px;object-fit:contain}
main{
    width:100%;max-width:820px;margin:0 auto;padding:clamp(22px,5vw,40px);
    background:#fff;border:1px solid var(--border);border-top:5px solid var(--red);
    border-radius:20px;box-shadow:0 18px 45px #101d4210;
}
.eyebrow{
    text-align:center;color:var(--red);font-size:11px;
    font-weight:900;letter-spacing:2px
}
h1{margin:8px 0;text-align:center;font-size:clamp(26px,4vw,34px);font-weight:900}
.subtitle{text-align:center;color:var(--muted);font-size:14px;line-height:1.6;margin:0 0 24px}
.station-box{
    padding:16px;background:#f8faff;border:1px solid var(--border);
    border-radius:12px;margin-bottom:22px
}
label,.field-label{display:block;margin:0 0 7px;font-size:13px;font-weight:750}
input,select,textarea{
    width:100%;min-width:0;min-height:46px;padding:11px 12px;
    border:1px solid #c7d4e5;border-radius:9px;background:#fff;
    color:var(--navy);font:inherit;font-size:15px;outline:none
}
input:focus,select:focus,textarea:focus{
    border-color:var(--blue);box-shadow:0 0 0 3px #21469b1a
}
#counter{max-width:180px}
#work[hidden],#next[hidden]{display:none!important}
#work{margin-top:22px}
.now-serving{
    text-align:center;margin:0;color:var(--muted);font-size:11px;
    font-weight:900;letter-spacing:2px
}
.number{
    margin:14px 0 24px;padding:22px 8px;border:1px solid #d7e5fb;
    border-radius:12px;background:#edf4ff;color:var(--blue);
    text-align:center;font-size:clamp(27px,6.2vw,46px);
    line-height:1.2;font-weight:950;overflow-wrap:anywhere
}
.section-title{
    display:flex;align-items:center;gap:10px;margin:24px 0 16px;
    padding-bottom:10px;border-bottom:1px solid var(--border);
    font-size:16px;font-weight:900
}
.section-number{
    display:inline-grid;place-items:center;width:30px;height:30px;
    border-radius:9px;background:#21469b;color:white;font-size:13px
}
.required-note{font-size:12px;color:var(--muted);margin:-5px 0 16px}
.required-star{color:var(--red)}
.form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:17px}
.field{min-width:0}
.full{grid-column:1/-1}
.unit{font-size:11px;color:var(--muted);font-weight:500}
.choice-row{display:flex;flex-wrap:wrap;gap:8px}
.choice{
    display:flex;align-items:center;gap:7px;padding:10px 12px;
    border:1px solid var(--border);border-radius:9px;background:#f8faff;
    font-size:13px;cursor:pointer
}
.choice input{width:16px;min-height:16px;height:16px;margin:0;accent-color:var(--blue)}
.pain-options{display:grid;grid-template-columns:repeat(11,minmax(0,1fr));gap:5px}
.pain-option{position:relative}
.pain-option input{
    position:absolute;opacity:0;width:1px;height:1px;min-height:1px
}
.pain-option span{
    display:grid;place-items:center;min-height:38px;border:1px solid var(--border);
    border-radius:7px;background:#f8faff;font-size:12px;font-weight:800;cursor:pointer
}
.pain-option input:checked+span{background:var(--blue);border-color:var(--blue);color:#fff}
.pain-option input:focus-visible+span{outline:3px solid var(--gold)}
textarea{min-height:100px;resize:vertical}
.btn{
    min-height:49px;padding:13px 16px;border:0;border-radius:10px;
    font:inherit;font-size:14px;font-weight:850;cursor:pointer
}
.btn:hover{filter:brightness(1.05)}
.btn:disabled{opacity:.6;cursor:wait}
.next{width:100%;margin-top:8px;background:var(--blue);color:#fff}
.complete{width:100%;margin-top:24px;background:var(--red);color:#fff}
.actions{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:12px}
.actions button{width:100%}
.recall{background:#e7edf6;color:var(--navy)}
.skip{background:#fff0d8;color:#8a4b00}
#status{min-height:24px;margin:18px 0 0;text-align:center;color:var(--muted);font-size:13px;line-height:1.5}
@media(max-width:600px){
    body{padding:12px}
    .lumc-header{padding:10px;gap:8px}
    .lumc-main-logo{width:40px;height:40px}
    .lumc-brand span{font-size:8px;letter-spacing:.3px}
    .partner-logos{gap:3px}
    .partner-logos img{width:22px;height:22px}
    main{padding:22px 16px;border-radius:15px}
    .form-grid{grid-template-columns:1fr}
    .full{grid-column:auto}
    .pain-options{gap:3px}
    .pain-option span{min-height:34px;font-size:11px}
    .actions{grid-template-columns:1fr}
}
</style>
</head>

<body>
<header class="lumc-header">
    <img class="lumc-main-logo" src="{{ asset('images/LUMCLOGO.png') }}" alt="LUMC Logo">
    <div class="lumc-brand">
        <strong>LA UNION MEDICAL CENTER</strong>
        <span>OUTPATIENT DEPARTMENT · VITAL SIGNS</span>
    </div>
    <div class="partner-logos">
        <img src="{{ asset('images/provinceofelyu.png') }}" alt="Province of La Union">
        <img src="{{ asset('images/LaUnionAgkaysa.png') }}" alt="La Union Agkaysa">
        <img src="{{ asset('images/BagongPilipinas.png') }}" alt="Bagong Pilipinas">
    </div>
</header>

<main>
    <div class="eyebrow">PATIENT QUEUE MANAGEMENT</div>
    <h1>VITAL SIGNS</h1>
    <p class="subtitle">Initial vital signs assessment and documentation.</p>

    <div class="station-box">
        <label for="counter">Station number</label>
        <input id="counter" type="number" min="1" step="1" value="1">
        <button id="next" class="btn next" type="button" onclick="nextPatient()">
            CALL NEXT NUMBER
        </button>
    </div>

    <section id="work" hidden>
        <p class="now-serving">NOW SERVING</p>
        <div class="number" id="ticket">---</div>

        <div class="section-title">
            <span class="section-number">1</span>
            Initial Vital Signs
        </div>
        <p class="required-note"><span class="required-star">*</span> Required fields</p>

        <form id="vitalsForm" onsubmit="submitVitals(event)">
            <div class="form-grid">
                <div class="field">
                    <label for="temperature">Temperature (°C) <span class="required-star">*</span></label>
                    <input id="temperature" name="temperature" type="number"
                        min="25" max="45" step="0.1" placeholder="e.g. 36.5" required>
                </div>

                <div class="field">
                    <label for="temperature_site">Temperature site <span class="required-star">*</span></label>
                    <select id="temperature_site" name="temperature_site" required>
                        <option value="">Select site</option>
                        <option value="Axilla">Axilla</option>
                        <option value="Oral">Oral</option>
                        <option value="Rectal">Rectal</option>
                        <option value="Tympanic">Tympanic</option>
                    </select>
                </div>

                <div class="field">
                    <label for="pulse_rate">Pulse rate (bpm) <span class="required-star">*</span></label>
                    <input id="pulse_rate" name="pulse_rate" type="number"
                        min="1" max="300" step="1" placeholder="e.g. 80" required>
                </div>

                <div class="field">
                    <label for="respiratory_rate">Respiratory rate (breaths/min) <span class="required-star">*</span></label>
                    <input id="respiratory_rate" name="respiratory_rate" type="number"
                        min="1" max="100" step="1" placeholder="e.g. 18" required>
                </div>

                <div class="field">
                    <label for="systolic_bp">Systolic BP <span class="unit">(mmHg)</span></label>
                    <input id="systolic_bp" name="systolic_bp" type="number"
                        min="1" max="300" placeholder="e.g. 120">
                </div>

                <div class="field">
                    <label for="diastolic_bp">Diastolic BP <span class="unit">(mmHg)</span></label>
                    <input id="diastolic_bp" name="diastolic_bp" type="number"
                        min="1" max="200" placeholder="e.g. 80">
                </div>

                <div class="field">
                    <label for="spo2">SpO₂ (%)</label>
                    <input id="spo2" name="spo2" type="number"
                        min="0" max="100" step="1" placeholder="e.g. 98">
                </div>

                <div class="field">
                    <label for="weight">Weight (kg)</label>
                    <input id="weight" name="weight" type="number"
                        min="0" max="500" step="0.1" placeholder="e.g. 60.5">
                </div>

                <div class="field">
                    <label for="height">Height (cm)</label>
                    <input id="height" name="height" type="number"
                        min="0" max="300" step="0.1" placeholder="e.g. 165">
                </div>

                <div class="field full">
                    <span class="field-label">Pain scale</span>
                    <div class="choice-row" style="margin-bottom:10px">
                        <label class="choice">
                            <input type="radio" name="pain_scale" value="N/A" checked>
                            N/A
                        </label>
                    </div>
                    <div class="pain-options">
                        <label class="pain-option"><input type="radio" name="pain_scale" value="0"><span>0</span></label>
                        <label class="pain-option"><input type="radio" name="pain_scale" value="1"><span>1</span></label>
                        <label class="pain-option"><input type="radio" name="pain_scale" value="2"><span>2</span></label>
                        <label class="pain-option"><input type="radio" name="pain_scale" value="3"><span>3</span></label>
                        <label class="pain-option"><input type="radio" name="pain_scale" value="4"><span>4</span></label>
                        <label class="pain-option"><input type="radio" name="pain_scale" value="5"><span>5</span></label>
                        <label class="pain-option"><input type="radio" name="pain_scale" value="6"><span>6</span></label>
                        <label class="pain-option"><input type="radio" name="pain_scale" value="7"><span>7</span></label>
                        <label class="pain-option"><input type="radio" name="pain_scale" value="8"><span>8</span></label>
                        <label class="pain-option"><input type="radio" name="pain_scale" value="9"><span>9</span></label>
                        <label class="pain-option"><input type="radio" name="pain_scale" value="10"><span>10</span></label>
                    </div>
                    <p class="required-note">0 = no pain; 10 = worst pain. Follow hospital assessment policy.</p>
                </div>

                <div class="field full">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" maxlength="2000"
                        placeholder="Enter relevant initial assessment notes, if needed."></textarea>
                </div>
            </div>

            <button id="completeBtn" class="btn complete" type="submit">
                SAVE VITAL SIGNS &amp; SEND TO REGISTRATION
            </button>
        </form>

        <div class="actions">
            <button class="btn recall" type="button" onclick="recallPatient()">RECALL NUMBER</button>
            <button class="btn skip" type="button" onclick="skipPatient()">SKIP / ABSENT</button>
        </div>
    </section>

    <p id="status" role="status" aria-live="polite"></p>
</main>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
let patient = null;

async function post(url, body = {}) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify(body)
    });

    const data = await response.json();
    if (!response.ok || data.status === 'error') {
        throw new Error(data.message || 'The request failed.');
    }
    return data;
}

async function nextPatient() {
    const counter = document.getElementById('counter').value;

    if (!counter || Number(counter) < 1 || !Number.isInteger(Number(counter))) {
        showMessage('Enter a valid whole-number station number.');
        return;
    }

    try {
        const data = await post('/queue/vitals/next/' + encodeURIComponent(counter));

        if (data.status !== 'success') {
            showMessage(data.message || 'No patients are waiting.');
            return;
        }

        patient = data.ticket;
        document.getElementById('ticket').textContent = patient.ticket_number;
        document.getElementById('work').hidden = false;
        document.getElementById('next').hidden = true;
        document.getElementById('vitalsForm').reset();
        document.querySelector('input[name="pain_scale"][value="N/A"]').checked = true;
        showMessage('');
    } catch (error) {
        showMessage(error.message);
    }
}

async function submitVitals(event) {
    event.preventDefault();
    if (!patient) {
        showMessage('Call a queue number first.');
        return;
    }

    const form = document.getElementById('vitalsForm');
    if (!form.reportValidity()) return;

    const values = {
        temperature: document.getElementById('temperature').value,
        temperature_site: document.getElementById('temperature_site').value,
        pulse_rate: document.getElementById('pulse_rate').value,
        respiratory_rate: document.getElementById('respiratory_rate').value,
        systolic_bp: document.getElementById('systolic_bp').value,
        diastolic_bp: document.getElementById('diastolic_bp').value,
        spo2: document.getElementById('spo2').value,
        pain_scale: document.querySelector('input[name="pain_scale"]:checked')?.value || 'N/A',
        weight: document.getElementById('weight').value,
        height: document.getElementById('height').value,
        notes: document.getElementById('notes').value.trim()
    };

    const button = document.getElementById('completeBtn');
    button.disabled = true;

    try {
        const data = await post('/queue/vitals/complete/' + patient.id, values);
        resetPatient();
        showMessage(data.message || 'Vital signs submitted.');
    } catch (error) {
        showMessage(error.message);
    } finally {
        button.disabled = false;
    }
}

function recallPatient() {
    if (!patient) return;
    showMessage('Recalling ' + patient.ticket_number + ' at Vital Signs.');
    speakNumber(patient.ticket_number);
}

async function skipPatient() {
    if (!patient || !confirm('Mark ' + patient.ticket_number + ' as absent/skipped?')) return;

    try {
        const data = await post('/queue/vitals/skip/' + patient.id);
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
                'Queue number ' + number + ', please proceed to Vital Signs.'
            )
        );
    }
}

function resetPatient() {
    patient = null;
    document.getElementById('work').hidden = true;
    document.getElementById('next').hidden = false;
    document.getElementById('vitalsForm').reset();
    document.querySelector('input[name="pain_scale"][value="N/A"]').checked = true;
}

function showMessage(message) {
    document.getElementById('status').textContent = message;
}
</script>
</body>
</html>
