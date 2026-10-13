
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LUMC | OPD Queue Display</title>
<style>
:root{--navy:#101d42;--blue:#203f91;--red:#d92332;--gold:#f2c94c}
*{box-sizing:border-box}
body{margin:0;background:var(--navy);font-family:"Segoe UI",Arial,sans-serif;color:white;min-height:100vh}
.topbar{min-height:82px;background:#fff;color:var(--navy);display:flex;align-items:center;gap:13px;padding:12px clamp(16px,3vw,38px);border-bottom:4px solid var(--red)}
.logo{width:50px;height:50px;object-fit:contain}
.brand{font-weight:900;font-size:clamp(14px,2vw,20px)}
.subtitle{font-size:11px;color:#697790;margin-top:4px;letter-spacing:1px;font-weight:700}
.logos{margin-left:auto;display:flex;gap:10px;align-items:center}
.logos img{width:34px;height:34px;object-fit:contain}
.heading{text-align:center;padding:24px 12px 12px}
.heading h1{font-size:clamp(22px,3vw,36px);margin:0}
.heading p{color:#d4deef;font-size:14px;margin:8px 0}
.gold-line{width:72px;height:4px;background:var(--gold);border-radius:4px;margin:14px auto}
.panels{display:grid;grid-template-columns:1fr 1fr;gap:18px;max-width:1500px;margin:12px auto;padding:12px clamp(14px,3vw,40px) 28px}
.panel{min-width:0;background:#172b53;border:1px solid #354b77;border-radius:18px;overflow:hidden;text-align:center}
.panel-head{padding:18px 12px;background:#1c3670;border-bottom:1px solid #3a4d7c}
.panel.reg .panel-head{background:#292c57;border-bottom-color:#7d394d}
.station-label{font-size:clamp(16px,2vw,24px);font-weight:800;letter-spacing:.8px}
.station-sub{font-size:12px;color:#d2dcf0;margin-top:5px}
.panel-body{padding:clamp(22px,4vw,48px) 14px}
.call-label{font-size:12px;letter-spacing:2px;color:#c5d2eb;font-weight:700}
.number{display:block;width:100%;white-space:nowrap;overflow:hidden;text-overflow:clip;font-size:clamp(24px,4.1vw,68px);font-weight:900;letter-spacing:-1.5px;line-height:1.25;color:#fff;margin:18px auto 20px;font-variant-numeric:tabular-nums}
.panel.vitals .number{color:#9ed0ff}
.panel.reg .number{color:#ffb0b8}
.counter{display:inline-block;background:#101d42;border:1px solid #50658e;border-radius:30px;padding:9px 18px;color:#f2c94c;font-size:13px;font-weight:800}
.wait{color:#d3deef;text-align:center;font-size:13px;letter-spacing:.7px;padding:8px 14px 24px}
@media(max-width:650px){.panels{grid-template-columns:1fr}.panel-body{padding:24px 12px}.number{font-size:clamp(28px,8vw,46px)}.logos img{width:25px;height:25px}}
</style>
</head>
<body>
<header class="topbar">
  <img class="logo" src="{{ asset('images/LUMCLOGO.png') }}" alt="LUMC">
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

<div class="heading">
  <h1>OPD QUEUE DISPLAY</h1>
  <p>Please proceed when your number is called.</p>
  <div class="gold-line"></div>
</div>

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

<div class="wait">PLEASE WAIT FOR YOUR NUMBER TO BE CALLED</div>

<script>
async function refreshQueue(){
  try{
    const response=await fetch("{{ url('/queue/display-data') }}",{
      headers:{'Accept':'application/json'},
      cache:'no-store'
    });
    if(!response.ok)return;
    const data=await response.json();

    const vitals=data.vitals?.[0]||null;
    const registration=data.registration?.[0]||null;

    document.getElementById('vitals').textContent=vitals?.ticket_number||'---';
    document.getElementById('vitalsCounter').textContent=
      vitals?.counter_number?'STATION '+vitals.counter_number:'STATION 1';

    document.getElementById('registration').textContent=
      registration?.ticket_number||'---';
    document.getElementById('registrationCounter').textContent=
      registration?.counter_number?'COUNTER '+registration.counter_number:'COUNTER 1';
  }catch(error){
    // Preserve the last successful display if the network temporarily fails.
  }
}
refreshQueue();
setInterval(refreshQueue,3000);
</script>
</body>
</html>

<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>LUMC OPD - Queue Display</title>
<style>
*{box-sizing:border-box}
body{margin:0;background:#102a43;color:white;font-family:Arial,sans-serif}
header{text-align:center;padding:22px 12px;background:#0b1f33}
header h1{margin:0;font-size:clamp(22px,3vw,38px)}
header p{color:#bdd1e1;margin:8px 0 0}
main{display:grid;grid-template-columns:1fr 1fr;min-height:75vh}
.station{display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:25px;border-right:2px solid #38566e}
.station:last-child{border:0}
.station h2{font-size:clamp(20px,3vw,34px);margin:0 0 25px}
.number{font-size:clamp(48px,10vw,110px);font-weight:900;overflow-wrap:anywhere;color:#72e3ee;background:#183c58;border:3px solid #416782;border-radius:20px;padding:20px;width:100%;max-width:600px}
.reg .number{color:#91f2b5}
.caption{font-size:clamp(16px,2vw,24px);margin-top:22px;color:#d5e4ee}
footer{text-align:center;color:#bdd1e1;padding:14px}
@media(max-width:650px){main{grid-template-columns:1fr}.station{border-right:0;border-bottom:2px solid #38566e;min-height:42vh}}
</style>
</head>
<body>
<header>
<h1>LA UNION MEDICAL CENTER</h1>
<p>OUTPATIENT DEPARTMENT — QUEUE NUMBERS</p>
</header>
<main>
<section class="station">
<h2>VITAL SIGNS</h2>
<div class="number" id="vitals">---</div>
<div class="caption">Proceed to Vital Signs</div>
</section>
<section class="station reg">
<h2>OPD REGISTRATION</h2>
<div class="number" id="registration">---</div>
<div class="caption">Proceed to OPD Clerk</div>
</section>
</main>
<footer>PLEASE WAIT FOR YOUR NUMBER TO BE CALLED</footer>
<script>
async function refreshQueue(){
  try{
    const response=await fetch('/queue/display-data',{headers:{'Accept':'application/json'},cache:'no-store'});
    if(!response.ok)throw new Error('Queue unavailable');
    const data=await response.json();
    document.getElementById('vitals').textContent=data.vitals?.[0]?.ticket_number||'---';
    document.getElementById('registration').textContent=data.registration?.[0]?.ticket_number||'---';
  }catch(e){
    // Keep the last displayed numbers if a temporary connection error occurs.
  }
}
refreshQueue();
setInterval(refreshQueue,3000);
</script>
</body>
</html> -->
