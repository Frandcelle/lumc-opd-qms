
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>LUMC | OPD Queue</title>
<style>
:root{--navy:#101d42;--blue:#203f91;--red:#d92332;--gold:#f2c94c;--white:#fff;--muted:#d5def0}
*{box-sizing:border-box}
body{margin:0;min-height:100vh;font-family:"Segoe UI",Arial,sans-serif;background:linear-gradient(135deg,#101d42 0%,#203f91 65%,#172654 100%);color:var(--white)}
.topbar{height:auto;min-height:82px;background:#fff;color:var(--navy);display:flex;align-items:center;gap:13px;padding:12px clamp(16px,4vw,48px);box-shadow:0 3px 14px #0002}
.logo{width:52px;height:52px;object-fit:contain;flex-shrink:0}
.brand{font-weight:900;font-size:clamp(13px,2vw,19px);line-height:1.3}
.tagline{font-size:10px;color:#78849a;letter-spacing:1px;font-weight:700;margin-top:3px}
.govlogos{margin-left:auto;display:flex;align-items:center;gap:10px}
.govlogos img{width:35px;height:35px;object-fit:contain}
main{min-height:calc(100vh - 82px);display:flex;justify-content:center;align-items:center;padding:34px 18px}
.card{width:min(100%,600px);padding:clamp(24px,5vw,46px);background:#fff;color:var(--navy);border-radius:20px;box-shadow:0 24px 70px #06112e55;text-align:center;border-top:5px solid var(--gold)}
.eyebrow{color:var(--blue);font-weight:800;letter-spacing:2px;font-size:12px}
h1{font-size:clamp(28px,5vw,42px);margin:12px 0 10px;line-height:1.15}
.intro{color:#64738a;font-size:15px;line-height:1.6;margin:0 auto 24px;max-width:410px}
.actions{display:grid;gap:12px}
button{width:100%;min-height:62px;border:0;border-radius:11px;font-size:17px;font-weight:800;color:#fff;cursor:pointer;transition:.18s}
button:hover{transform:translateY(-2px);filter:brightness(1.06)}
button:disabled{opacity:.6;cursor:wait;transform:none}
.newbtn{background:var(--red);box-shadow:0 7px 18px #d923322b}
.oldbtn{background:var(--blue)}
.small{font-size:12px;font-weight:500;display:block;margin-top:4px;opacity:.9}
.ticket{margin-top:22px;padding:24px 14px;border:1px dashed #b7c4dc;background:#f5f7fc;border-radius:14px}
.ticket[hidden],.actions[hidden]{display:none}
.ticket-label{font-size:12px;font-weight:800;letter-spacing:1.5px;color:#64738a}
.ticket-number{font-size:clamp(32px,7vw,52px);font-weight:900;color:var(--blue);margin:18px 0;overflow-wrap:anywhere}
.ticket-note{color:#64738a;line-height:1.5}
.done{background:var(--navy);margin-top:12px}
.footer{text-align:center;color:#e0e7f5;font-size:11px;letter-spacing:.8px;margin-top:24px}
.status{font-size:13px;color:#b42332;min-height:20px;margin:12px 0 0;overflow-wrap:anywhere}
@media(max-width:520px){.govlogos{gap:5px}.govlogos img{width:26px;height:26px}.logo{width:44px;height:44px}.topbar{gap:9px}.card{border-radius:16px}}
</style>
</head>
<body>
<header class="topbar">
  <img class="logo" src="{{ asset('images/LUMCLOGO.png') }}" alt="LUMC">
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
  <p class="intro">Kumuha ng number, sagutan ang triage form, at maghintay na tawagin sa Vital Signs.</p>

  <div class="actions" id="actions">
    <button class="newbtn" onclick="getTicket('new')">
      GET QUEUE NUMBER
      <span class="small">New or returning OPD visit</span>
    </button>
    <button class="oldbtn" onclick="getTicket('old')">
      EXISTING PATIENT
      <span class="small">May existing hospital record</span>
    </button>
  </div>

  <div class="ticket" id="ticket" hidden>
    <div class="ticket-label">YOUR QUEUE NUMBER</div>
    <div class="ticket-number" id="number">OPD-000</div>
    <p class="ticket-note">Please fill out your paper triage form and proceed to the Vital Signs waiting area.</p>
    <button class="done" onclick="resetKiosk()">DONE</button>
  </div>

  <p class="status" id="status" role="status" aria-live="polite"></p>
  <div class="footer">PLEASE WAIT FOR YOUR NUMBER TO BE CALLED</div>
</section>
</main>

<script>
const csrf=document.querySelector('meta[name="csrf-token"]').content;
let busy=false;

async function getTicket(type){
  if(busy)return;
  busy=true;
  const buttons=document.querySelectorAll('#actions button');
  buttons.forEach(b=>b.disabled=true);
  const status=document.getElementById('status');
  status.textContent='Generating queue number...';

  try{
    const response=await fetch("{{ url('/queue/issue') }}",{
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'Accept':'application/json',
        'X-CSRF-TOKEN':csrf
      },
      body:JSON.stringify({patient_type:type})
    });
    const data=await response.json();
    if(!response.ok||data.status!=='success'){
      throw new Error(data.message||'Unable to issue number. Please ask the OPD staff for help.');
    }
    document.getElementById('number').textContent=data.data.ticket_number;
    document.getElementById('actions').hidden=true;
    document.getElementById('ticket').hidden=false;
    status.textContent='';
  }catch(error){
    status.textContent=error.message||'Connection error. Please ask the OPD staff for help.';
  }finally{
    busy=false;
    buttons.forEach(b=>b.disabled=false);
  }
}

function resetKiosk(){
  document.getElementById('ticket').hidden=true;
  document.getElementById('actions').hidden=false;
  document.getElementById('status').textContent='';
}
</script>
</body>
</html>


<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>LUMC OPD Queue</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Arial,sans-serif;background:#f1f6fa;color:#15364b;display:grid;place-items:center;min-height:100vh;padding:20px}
.card{background:white;width:min(100%,600px);padding:36px 24px;border-radius:22px;text-align:center;box-shadow:0 8px 30px #15364b18}
.brand{font-size:14px;font-weight:bold;letter-spacing:2px;color:#087e8b}
h1{font-size:clamp(28px,5vw,42px);margin:12px 0}
p{color:#647887}
button{width:100%;padding:24px 12px;border:0;border-radius:14px;font-size:23px;font-weight:bold;cursor:pointer;margin-top:14px;color:white}
button:disabled{opacity:.6;cursor:wait}
.primary{background:#087e8b}.secondary{background:#1765a3}
.number{font-size:clamp(32px,8vw,56px);font-weight:900;overflow-wrap:anywhere;color:#087e8b;margin:20px 0}
.ticket{margin-top:24px;border:2px dashed #9bb9c7;border-radius:16px;padding:22px}
.small{font-size:13px}
</style>
</head>
<body>
<div class="card">
  
<div class="brandbar qms-brandbar">
    <img src="{{ asset('images/LUMCLOGO.png') }}" alt="LUMC Logo" class="logo-main">

    <div class="brand-heading">
        <div class="brand-name">LA UNION MEDICAL CENTER</div>
        <div class="brand-subtitle">OUTPATIENT DEPARTMENT</div>
    </div>

    <div class="government-logos">
        <img src="{{ asset('images/BagongPilipinas.png') }}" alt="Bagong Pilipinas">
        <img src="{{ asset('images/provinceofelyu.png') }}" alt="Province of La Union">
        <img src="{{ asset('images/LaUnionAgkaysa.png') }}" alt="La Union Agkaysa">
    </div>
</div>

  <h1>OPD QUEUE</h1>
  <p>Kumuha ng queue number at maghintay na tawagin.</p>

  <div id="buttons">
    <button class="primary" onclick="getTicket('new',this)">GET QUEUE NUMBER</button>
    <button class="secondary" onclick="getTicket('old',this)">EXISTING PATIENT</button>
    <p class="small">Pumila ayon sa number na ibibigay ng system.</p>
  </div>

  <div id="ticket" class="ticket" hidden>
    <p>YOUR QUEUE NUMBER</p>
    <div id="number" class="number">OPD-000</div>
    <p id="type"></p>
    <p>Proceed to the Vital Signs waiting area.</p>
    <button class="primary" onclick="resetKiosk()">DONE</button>
  </div>
  <p id="message" role="status"></p>
</div>
<script>
async function getTicket(type,button){
  const buttons=document.querySelectorAll('#buttons button');
  buttons.forEach(b=>b.disabled=true);
  const message=document.getElementById('message');
  message.textContent='Generating your number...';
  try{
    const response=await fetch('/queue/issue',{
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'Accept':'application/json',
        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
      },
      body:JSON.stringify({patient_type:type})
    });
    const data=await response.json();
    if(!response.ok||data.status!=='success')throw new Error(data.message||'Could not issue number.');
    document.getElementById('number').textContent=data.data.ticket_number;
    document.getElementById('type').textContent=type==='new'?'NEW PATIENT':'EXISTING PATIENT';
    document.getElementById('buttons').hidden=true;
    document.getElementById('ticket').hidden=false;
    message.textContent='';
  }catch(e){message.textContent=e.message||'Connection error. Please ask the staff for help.';}
  finally{buttons.forEach(b=>b.disabled=false);}
}
function resetKiosk(){
  document.getElementById('ticket').hidden=true;
  document.getElementById('buttons').hidden=false;
  document.getElementById('message').textContent='';
}
</script>
</body>
</html> -->
